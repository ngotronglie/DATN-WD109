<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Order::with([
            'voucher',
            'orderDetails.productVariant.color',
            'orderDetails.productVariant.capacity',
            'orderDetails.productVariant.product',
            'refundRequest' // ✅ Thêm dòng này
        ])->orderByDesc('created_at');

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', (int)$request->status);
        }

        $orders = $query->paginate(10);
        return view('layouts.admin.order.list', compact('orders'));
    }

    // Cập nhật trạng thái đơn hàng (AJAX)
    public function updateStatus(Request $request, $id)
    {
        \DB::beginTransaction();

        try {
            $order = Order::with('orderDetails.productVariant')->findOrFail($id);
            $newStatus = (int)$request->input('status');
            $oldStatus = $order->status;

            // Nếu hủy đơn hàng (status = 6) và trước đó chưa bị hủy
            if ($newStatus === 6 && $oldStatus !== 6) {
                foreach ($order->orderDetails as $detail) {
                    // Cập nhật số lượng tồn kho cho biến thể sản phẩm
                    if ($detail->productVariant) {
                        $detail->productVariant->increment('quantity', $detail->quantity);
                    }

                    // Nếu là sản phẩm flash sale, cập nhật lại số lượng tồn kho flash sale
                    if ($detail->flash_sale_id) {
                        $flashSaleProduct = \App\Models\FlashSaleProduct::where('flash_sale_id', $detail->flash_sale_id)
                            ->where('product_variant_id', $detail->product_variant_id)
                            ->lockForUpdate() // Khóa bản ghi để tránh race condition
                            ->first();

                        if ($flashSaleProduct) {
                            // Cập nhật remaining_stock và sold_quantity một cách thủ công
                            $flashSaleProduct->remaining_stock += $detail->quantity;
                            $flashSaleProduct->sold_quantity = max(0, $flashSaleProduct->sold_quantity - $detail->quantity);

                            // Đảm bảo remaining_stock không vượt quá initial_stock
                            if ($flashSaleProduct->remaining_stock > $flashSaleProduct->initial_stock) {
                                $flashSaleProduct->remaining_stock = $flashSaleProduct->initial_stock;
                            }

                            $flashSaleProduct->save();
                        }
                    }
                }
            }
            // Nếu đơn hàng đang bị hủy (status = 6) và được cập nhật sang trạng thái khác
            elseif ($oldStatus === 6 && $newStatus !== 6) {
                foreach ($order->orderDetails as $detail) {
                    // Giảm số lượng tồn kho cho biến thể sản phẩm
                    if ($detail->productVariant) {
                        if ($detail->productVariant->quantity >= $detail->quantity) {
                            $detail->productVariant->decrement('quantity', $detail->quantity);
                        } else {
                            throw new \Exception('Không đủ số lượng tồn kho cho sản phẩm: ' . ($detail->productVariant->product->name ?? ''));
                        }
                    }

                    // Nếu là sản phẩm flash sale, giảm số lượng tồn kho flash sale
                    if ($detail->flash_sale_id) {
                        $flashSaleProduct = \App\Models\FlashSaleProduct::where('flash_sale_id', $detail->flash_sale_id)
                            ->where('product_variant_id', $detail->product_variant_id)
                            ->lockForUpdate() // Khóa bản ghi để tránh race condition
                            ->first();

                        if ($flashSaleProduct) {
                            if ($flashSaleProduct->remaining_stock >= $detail->quantity) {
                                // Cập nhật remaining_stock và sold_quantity một cách thủ công
                                $flashSaleProduct->remaining_stock -= $detail->quantity;
                                $flashSaleProduct->sold_quantity += $detail->quantity;

                                // Đảm bảo sold_quantity không vượt quá initial_stock
                                if ($flashSaleProduct->sold_quantity > $flashSaleProduct->initial_stock) {
                                    $flashSaleProduct->sold_quantity = $flashSaleProduct->initial_stock;
                                    $flashSaleProduct->remaining_stock = 0;
                                }

                                $flashSaleProduct->save();
                            } else {
                                throw new \Exception('Không đủ số lượng tồn kho flash sale cho sản phẩm: ' . ($detail->productVariant->product->name ?? ''));
                            }
                        }
                    }
                }
            }

            // Cập nhật trạng thái đơn hàng
            $order->status = $newStatus;

            // Nếu đơn hàng được đánh dấu là "Đã giao hàng" (status = 5)
            if ($newStatus === 5) {
                // Nếu là COD thì đánh dấu đã thu tiền COD, nếu là VNPAY thì đã thanh toán trước đó (2)
                if ((int)$order->status_method === 0) {
                    $order->status_method = 1; // thu COD khi giao
                }
                if (!$order->payment_method) {
                    $order->payment_method = 'cod';
                }
            }

            $order->save();
            \DB::commit();

            return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi cập nhật đơn hàng: ' . $e->getMessage());
        }
    }

    // Admin thao tác hoàn tiền ngay tại bước đóng gói (status = 2)
    public function initiateRefund(Request $request, $id)
    {
        $order = Order::with('refundRequest')->findOrFail($id);

        // Chỉ cho phép hoàn tiền với đơn thanh toán online (VD: vnpay)
        $paymentMethod = strtolower((string) $order->payment_method);
        if (!in_array($paymentMethod, ['vnpay', 'online'])) {
            return back()->with('error', 'Hoàn tiền chỉ áp dụng cho đơn thanh toán online. Đơn COD không hỗ trợ hoàn tiền.');
        }

        if (!in_array((int)$order->status, [1, 2])) {
            return back()->with('error', 'Chỉ có thể khởi tạo hoàn tiền khi đơn đang ở bước Đã xác nhận/Đang xử lý.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        if ($order->refundRequest) {
            return back()->with('error', 'Đơn hàng đã có yêu cầu hoàn.');
        }

        $refund = RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'reason' => $request->input('reason'),
            'refund_requested_at' => now(),
        ]);

        // Chuyển trạng thái đơn sang 7: đã xác nhận yêu cầu hoàn hàng
        $order->status = 7;
        $order->save();

        // Gửi thông báo email cho người dùng kèm link nhập thông tin ngân hàng
        try {
            $user = $order->user;
            if ($user && $user->email) {
                \Mail::send('emails.refund-init', ['order' => $order, 'refund' => $refund], function ($message) use ($user, $order) {
                    $message->to($user->email);
                    $message->subject('Yêu cầu hoàn tiền cho đơn #' . $order->order_code);
                });
            }
        } catch (\Throwable $e) {
            \Log::error('Không thể gửi mail hoàn tiền: ' . $e->getMessage());
        }

        return back()->with('success', 'Đã khởi tạo hoàn tiền và thông báo khách hàng nhập thông tin ngân hàng.');
    }


    public function show($id)
    {
        $order = \App\Models\Order::with(['voucher', 'orderDetails.productVariant.color', 'orderDetails.productVariant.capacity', 'orderDetails.productVariant.product'])
            ->findOrFail($id);
        return view('layouts.admin.order.detail', compact('order'));
    }
    public function refundRequests()
    {
        $refunds = RefundRequest::with('order')->orderByDesc('created_at')->paginate(10);
        return view('layouts.admin.refunds.list', compact('refunds'));
    }
    public function showRefundDetail($id)
    {
        $refund = RefundRequest::findOrFail($id); // tạo biến $refund từ DB
        return view('layouts.admin.refunds.detail', compact('refund'));
    }

    public function approveRefund($id)
    {
        $refund = RefundRequest::findOrFail($id);

        // Không cho duyệt nếu thiếu thông tin ngân hàng của khách
        if (empty($refund->bank_name) || empty($refund->bank_number) || empty($refund->account_name)) {
            return redirect()->back()->with('error', 'Không thể hoàn tiền: thiếu thông tin ngân hàng của khách hàng.');
        }
        $refund->refund_completed_at = now();
        $refund->refunded_by = Auth::user()->name;
        $refund->save();

        // ✅ Cập nhật trạng thái đơn hàng thành 8 (Đã hoàn)
        $order = Order::find($refund->order_id);
        if ($order) {
            $order->status = 9; // <-- Đã sửa
            $order->save();
        }

        return redirect()->back()->with('success', 'Đã đánh dấu hoàn tiền và cập nhật trạng thái đơn hàng.');
    }


    public function uploadRefundProof(Request $request, $id)
    {
        $refund = RefundRequest::findOrFail($id);

        // Không cho upload/hoàn nếu thiếu thông tin ngân hàng của khách
        if (empty($refund->bank_name) || empty($refund->bank_number) || empty($refund->account_name)) {
            return redirect()->back()->with('error', 'Thiếu thông tin ngân hàng của khách hàng. Vui lòng yêu cầu khách bổ sung trước khi hoàn tiền.');
        }

        $request->validate([
            'proof_image' => 'required|image|max:2048',
        ]);

        if ($refund->proof_image) {
            Storage::delete('public/' . $refund->proof_image);
        }

        $path = $request->file('proof_image')->store('refunds/proofs', 'public');
        $refund->proof_image = $path;

        // ✅ Cập nhật thông tin người hoàn và thời gian hoàn nếu chưa có
        if (!$refund->refund_completed_at) {
            $refund->refund_completed_at = now();
            $refund->refunded_by = Auth::user()->name;
        }

        $refund->save();

        // ✅ Cập nhật trạng thái đơn hàng sang Hoàn tiền thành công
        $order = Order::find($refund->order_id);
        if ($order) {
            $order->status = 9; // Hoàn tiền thành công
            $order->save();
        }

        return back()->with('success', 'Đã cập nhật ảnh chuyển khoản và trạng thái đơn hàng.');
    }
    public function verify($id)
    {
        $refund = RefundRequest::findOrFail($id);
        $refund->status = '1'; // Đã xác thực
        $refund->save();

        // Cập nhật trạng thái đơn hàng thành 7
        $order = Order::find($refund->order_id);
        if ($order) {
            $order->status = 7; // Đã hoàn tiền
            $order->save();
        }

        return redirect()->back()->with('success', 'Yêu cầu hoàn đã được xác thực.');
    }

    public function reject($id)
    {
        $refund = RefundRequest::findOrFail($id);
        $refund->status = '2'; // Đã từ chối
        $refund->save();

        // Cập nhật trạng thái đơn hàng: 11 -> 12 (Không hoàn hàng), ngược lại giữ 10 (không xác nhận hoàn tiền)
        $order = Order::find($refund->order_id);
        if ($order) {
            if ((int)$order->status === 11) {
                $order->status = 12; // Không hoàn hàng
            } else {
                $order->status = 10; // Không xác nhận yêu cầu hoàn tiền
            }
            $order->save();
        }

        return redirect()->back()->with('success', 'Yêu cầu đã bị từ chối.');
    }
    public function confirmReceiveBack($refundId)
    {
        $refund = RefundRequest::findOrFail($refundId);
        $order = $refund->order;

        // Cho phép duyệt khi ở trạng thái 7 (luồng cũ) hoặc 11 (Đang yêu cầu hoàn hàng)
        if (!in_array((int)$order->status, [7, 11])) {
            return back()->with('error', 'Đơn hàng không ở trạng thái chờ xác nhận hoàn hàng.');
        }

        // Cập nhật thời gian đã nhận hàng hoàn
        $refund->received_back_at = now();
        $refund->save();

        // Cập nhật trạng thái đơn hàng sang "Đã hoàn hàng"
        $order->status = 8;
        $order->save();

        return back()->with('success', 'Xác nhận duyệt hoàn hàng thành công.');
    }
}
