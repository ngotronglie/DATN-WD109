<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundRequest;
use App\Jobs\AutoCancelFailedDeliveryOrder;
use App\Jobs\AutoMarkDelivered;
use App\Jobs\AutoMarkReceivedSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

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

            // Chặn cập nhật trạng thái nếu là đơn VNPAY chưa thanh toán
            $isVnpUnpaid = strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) === 0;
            if ($isVnpUnpaid) {
                \DB::rollBack();
                return redirect()->back()->with('error', 'Đơn VNPAY chưa thanh toán, không thể thao tác cập nhật trạng thái.');
            }

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
            $wasStatus = (int)$order->status;
            $order->status = $newStatus;

            // Nếu chuyển sang Đang vận chuyển (4) hoặc Đã giao/hoàn thành (5), xóa yêu cầu hoàn tiền nếu có
            if (in_array($newStatus, [4, 5]) && $order->refundRequest) {
                $order->refundRequest->delete();
            }

            // Nếu đơn hàng được đánh dấu là "Đã giao hàng" (status = 5)
            if ($newStatus === 5) {
                // Nếu là COD thì đánh dấu đã thu tiền COD, nếu là VNPAY thì đã thanh toán trước đó (2)
                if ((int)$order->status_method === 0) {
                    $order->status_method = 1; // thu COD khi giao
                }
                if (!$order->payment_method) {
                    $order->payment_method = 'cod';
                }
                // Ghi nhận thời điểm giao hàng
                if (empty($order->delivered_at)) {
                    $order->delivered_at = now();
                }
            }

            $order->save();

            // If marked as Delivered (5), schedule auto mark as 'Đã giao thành công' (15) after 3 days
            if ($newStatus === 5) {
                AutoMarkReceivedSuccess::dispatch($order->id)->delay(now()->addDays(3));
            }

            // Nếu chuyển sang Đang giao hàng (4), lên lịch tự động đánh dấu đã giao (5)
            // Chỉ áp dụng cho đơn COD và sau 30 phút nếu khách chưa xác nhận
            if ($wasStatus !== 4 && $newStatus === 4) {
                $paymentMethod = strtolower((string) $order->payment_method);
                if ($paymentMethod === 'cod') {
                    AutoMarkDelivered::dispatch($order->id)->delay(now()->addMinutes(30));
                }
            }
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

        if (!in_array((int)$order->status, [1, 2, 6, 13])) {
            return back()->with('error', 'Chỉ có thể khởi tạo hoàn tiền khi đơn ở trạng thái Đã xác nhận/Đang xử lý/Đã hủy/Giao hàng thất bại.');
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
            'type' => 'admin_refund',
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
        $order = \App\Models\Order::with([
                'voucher',
                'orderDetails.productVariant.color',
                'orderDetails.productVariant.capacity',
                'orderDetails.productVariant.product',
                'refundRequest'
            ])
            ->findOrFail($id);
        return view('layouts.admin.order.detail', compact('order'));
    }
    public function refundRequests()
    {
        $type = request('type');
        $query = RefundRequest::with('order')->orderByDesc('created_at');
        if ($type === 'admin_refund') {
            $query->where('type', 'admin_refund');
        } elseif ($type === 'return') {
            $query->where('type', 'return');
        }
        $refunds = $query->paginate(10);
        if ($type) {
            $refunds->appends(['type' => $type]);
        }
        return view('layouts.admin.refunds.list', compact('refunds', 'type'));
    }
    public function showRefundDetail($id)
    {
        $refund = RefundRequest::findOrFail($id); // tạo biến $refund từ DB
        return view('layouts.admin.refunds.detail', compact('refund'));
    }

    // Bước 2: Admin duyệt yêu cầu hoàn hàng (chỉ đổi trạng thái sang 7)
    public function approveReturn($id)
    {
        $refund = RefundRequest::findOrFail($id);
        $order = Order::findOrFail($refund->order_id);

        // Chỉ duyệt khi đang ở trạng thái 11 (Đang yêu cầu hoàn hàng)
        if ((int)$order->status !== 11) {
            return back()->with('error', 'Chỉ có thể duyệt khi đơn đang ở trạng thái yêu cầu hoàn hàng.');
        }

        $order->status = 7; // Hoàn hàng đã được duyệt
        $order->save();

        // TODO: gửi thông báo/email cho khách nếu cần
        return back()->with('success', 'Đã duyệt yêu cầu hoàn hàng. Đang chờ khách xác nhận đã trả hàng.');
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

        // ✅ Gửi email cho khách kèm minh chứng hoàn tiền
        try {
            $user = $order?->user;
            if ($user && $user->email) {
                $proofUrl = $refund->proof_image ? asset('storage/' . $refund->proof_image) : null;
                Mail::send('emails.refund-completed', [
                    'order' => $order,
                    'refund' => $refund,
                    'proofUrl' => $proofUrl,
                ], function ($message) use ($user, $order) {
                    $message->to($user->email);
                    $message->subject('Hoàn tiền thành công cho đơn #' . ($order->order_code ?? $order->id));
                });
            }
        } catch (\Throwable $e) {
            \Log::warning('Không thể gửi email hoàn tiền: ' . $e->getMessage());
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
        $order->delivered_at = now(); // Stamp delivered_at
        $order->save();

        return back()->with('success', 'Xác nhận duyệt hoàn hàng thành công.');
    }

    // Giao hàng thành công - chuyển từ đang vận chuyển sang đã giao hàng
    public function deliverySuccess($id)
    {
        \DB::beginTransaction();
        
        try {
            $order = Order::findOrFail($id);
            
            // Chỉ cho phép khi đơn hàng đang ở trạng thái "Đang vận chuyển" (4)
            if ((int)$order->status !== 4) {
                return back()->with('error', 'Chỉ có thể đánh dấu giao thành công khi đơn hàng đang vận chuyển.');
            }
            
            // Cập nhật trạng thái sang "Đã giao hàng" (5)
            $order->status = 5;
            
            // Nếu là COD thì đánh dấu đã thu tiền COD
            if ((int)$order->status_method === 0) {
                $order->status_method = 1; // thu COD khi giao
            }
            if (!$order->payment_method) {
                $order->payment_method = 'cod';
            }
            // Ghi nhận thời điểm giao hàng
            if (empty($order->delivered_at)) {
                $order->delivered_at = now();
            }
            
            $order->save();
            
            // Schedule auto mark as 'Đã giao thành công' (15) after 3 days
            AutoMarkReceivedSuccess::dispatch($order->id)->delay(now()->addDays(3));
            \DB::commit();
            
            return back()->with('success', 'Đã đánh dấu giao hàng thành công.');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
    
    // Giao hàng thất bại - chuyển sang trạng thái giao hàng thất bại
    public function deliveryFailed($id)
    {
        \DB::beginTransaction();
        
        try {
            $order = Order::findOrFail($id);
            
            // Chỉ cho phép khi đơn hàng đang ở trạng thái "Đang giao hàng" (4)
            if ((int)$order->status !== 4) {
                return back()->with('error', 'Chỉ có thể đánh dấu giao thất bại khi đơn hàng đang giao hàng.');
            }
            
            // Cập nhật trạng thái sang "Giao hàng thất bại" (13)
            $order->status = 13;
            $order->save();
            
            \DB::commit();
            
            return back()->with('success', 'Đã đánh dấu giao hàng thất bại.');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
    
    // Giao hàng lại - chuyển từ giao hàng thất bại về đang giao hàng
    public function redeliver($id)
    {
        \DB::beginTransaction();
        
        try {
            $order = Order::findOrFail($id);
            
            // Chỉ cho phép khi đơn hàng đang ở trạng thái "Giao hàng thất bại" (13)
            if ((int)$order->status !== 13) {
                return back()->with('error', 'Chỉ có thể giao hàng lại khi đơn hàng ở trạng thái giao hàng thất bại.');
            }
            
            // Cập nhật trạng thái sang "Đang giao hàng" (4)
            $order->status = 4;
            $order->save();

            // Lên lịch tự động đánh dấu đã giao (5) sau 30 giây
            AutoMarkDelivered::dispatch($order->id)->delay(now()->addSeconds(30));
            \DB::commit();
            
            return back()->with('success', 'Đã chuyển đơn hàng sang trạng thái đang giao hàng để giao lại.');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }

    // COD không nhận hàng - chuyển sang trạng thái COD không nhận hàng
    public function codNotReceived($id)
    {
        \DB::beginTransaction();
        
        try {
            $order = Order::findOrFail($id);
            
            // Chỉ cho phép khi đơn hàng đang ở trạng thái "Đang giao hàng" (4) và là COD
            if ((int)$order->status !== 4) {
                return back()->with('error', 'Chỉ có thể đánh dấu COD không nhận hàng khi đơn hàng đang giao hàng.');
            }
            
            if (strtolower((string)$order->payment_method) !== 'cod') {
                return back()->with('error', 'Chỉ áp dụng cho đơn hàng COD.');
            }
            
            // Cập nhật trạng thái sang "COD không nhận hàng" (14)
            $order->status = 14;
            $order->save();
            
            \DB::commit();
            
            return back()->with('success', 'Đã đánh dấu COD không nhận hàng.');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
}
