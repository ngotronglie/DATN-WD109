<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Trong OrderController:
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
            return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
        }

        $orders = Order::with('refundRequest')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('account.order', compact('orders'));
    }
    public function show($id)
    {
        $user = auth()->user();

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['orderDetails.product', 'refundRequest']) // thêm refundRequest
            ->firstOrFail();

        return view('account.orderdetail', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string',
            'reason_input' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Chỉ cho phép yêu cầu hoàn tiền cho đơn thanh toán online và không ở trạng thái đang vận chuyển/đã hoàn thành
        $order = Order::where('id', $request->order_id)->where('user_id', Auth::id())->firstOrFail();
        if (in_array((int) $order->status, [4, 5])) {
            return back()->with('error', 'Không thể yêu cầu hoàn tiền khi đơn đang vận chuyển hoặc đã hoàn thành.');
        }
        $paymentMethod = strtolower((string) $order->payment_method);
        if (!in_array($paymentMethod, ['vnpay', 'online'])) {
            return back()->with('error', 'Hoàn tiền chỉ áp dụng cho đơn thanh toán online. Đơn COD không hỗ trợ hoàn tiền.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('refund_images', 'public');
        }

        RefundRequest::create([
            'order_id' => $request->order_id,
            'user_id' => Auth::id(),
            'reason' => $request->reason == 'Khác' ? $request->reason_input : $request->reason,
            'image' => $imagePath,
            'refund_requested_at' => now(),
        ]);

        return back()->with('success', 'Đã gửi yêu cầu hoàn tiền.');
    }


    public function returnOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Cho phép yêu cầu hoàn hàng khi đơn ở trạng thái ĐÃ GIAO (5) hoặc ĐÃ GIAO THÀNH CÔNG (15)
        if (!in_array((int)$order->status, [5, 15])) {
            return redirect()->route('user.orders.show', $order->id)->with('error', 'Chỉ có thể yêu cầu hoàn hàng khi đơn đã giao.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
            'reason_input' => 'nullable|string|max:1000',
            // Chấp nhận bất kỳ định dạng ảnh hợp lệ, không giới hạn phần mở rộng
            'image' => 'nullable|image|max:2048',
            'bank_name' => 'required|string|max:255',
            'bank_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('refund_images', 'public');
        }

        RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => 'return',
            'reason' => $request->reason === 'Khác' ? ($request->reason_input ?? 'Khác') : $request->reason,
            'image' => $imagePath,
            'refund_requested_at' => now(),
            'bank_name' => $request->bank_name,
            'bank_number' => $request->bank_number,
            'account_name' => $request->account_name,
        ]);

        $order->status = 11; // Đang yêu cầu hoàn hàng (hoàn hàng từ phía khách)
        $order->save();

        return redirect()->route('user.orders.show', $order->id)->with('success', 'Đã gửi yêu cầu hoàn hàng, vui lòng chờ quản trị viên duyệt.');
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if (!in_array((int)$order->status, [0,1])) {
            return redirect()->back()->with('error', 'Chỉ có thể hủy đơn ở trạng thái chờ xác nhận hoặc đã xác nhận.');
        }

        // Khôi phục tồn kho cho các sản phẩm trong đơn
        foreach ($order->orderDetails as $detail) {
            if ($detail->productVariant) {
                $detail->productVariant->increment('quantity', (int)$detail->quantity);
            }
        }

        $order->status = 6; // Đã hủy
        $order->save();

        return redirect()->route('account.order')->with('success', 'Đơn hàng đã được hủy thành công.');
    }

    public function fillInfo($id)
    {
        $refund = RefundRequest::findOrFail($id);

        // Kiểm tra quyền: chỉ chủ đơn hàng được điền
        if (auth()->id() !== $refund->user_id) {
            abort(403);
        }

        return view('account.fillinfo', compact('refund'));
    }

    public function updateInfo(Request $request, $id)
    {
        $refund = RefundRequest::findOrFail($id);

        if (auth()->id() !== $refund->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
        ]);

        // Không yêu cầu ảnh/sent_back cho hoàn tiền do admin

        // ✅ Cập nhật thời gian gửi hàng
        // giữ nguyên lịch sử nếu có nhưng không bắt buộc

        $refund->update($validated);

        return redirect()->route('user.orders.show', $refund->order_id)->with('success', 'Thông tin ngân hàng để hoàn tiền đã được cập nhật.');
    }

    public function confirmReceived($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Cho phép xác nhận khi đơn đang vận chuyển (4) hoặc đã giao (5)
        if (!in_array((int)$order->status, [4, 5])) {
            return redirect()->back()->with('error', 'Chỉ xác nhận khi đơn đang vận chuyển hoặc đã giao.');
        }

        // Khi khách xác nhận, chuyển sang "Đã giao thành công" (15)
        $order->status = 15;

        // Nếu là COD và chưa đánh dấu thanh toán, coi như đã thu COD khi khách xác nhận nhận hàng
        if (strtolower((string)$order->payment_method) === 'cod' && (int)($order->status_method ?? 0) === 0) {
            $order->status_method = 1; // Đã thanh toán (COD)
        }
        // Ghi nhận thời điểm khách xác nhận đã nhận hàng để ẩn nút lần sau
        $order->received_confirmed_at = now();
        $order->save();

        return redirect()->route('user.orders.show', $order->id)->with('success', 'Đã xác nhận nhận hàng. Cảm ơn bạn!');
    }

    // Bước 3: Khách xác nhận đã trả hàng sau khi admin duyệt (status phải là 7)
    public function markReturned($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->with('refundRequest')->firstOrFail();

        // Chỉ cho phép khi admin đã duyệt hoàn hàng (status = 7)
        if ((int)$order->status !== 7) {
            return redirect()->back()->with('error', 'Chỉ có thể xác nhận đã trả hàng sau khi yêu cầu được admin duyệt.');
        }

        // Ghi nhận thời điểm khách xác nhận đã gửi/trả hàng
        if ($order->refundRequest) {
            $order->refundRequest->received_back_at = now();
            $order->refundRequest->save();
        }

        // Cập nhật sang trạng thái Đã hoàn hàng (8)
        $order->status = 8;
        $order->save();

        return redirect()->route('user.orders.show', $order->id)->with('success', 'Bạn đã xác nhận đã trả hàng. Chúng tôi sẽ kiểm tra và hoàn tiền sớm nhất.');
    }

    // Đặt lại hàng: đưa toàn bộ sản phẩm từ đơn cũ vào giỏ hàng hiện tại
    public function reorder($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->with('orderDetails')->firstOrFail();

        // Chỉ cho phép đặt lại khi đơn đã hủy
        if ((int)$order->status !== 6) {
            return redirect()->back()->with('error', 'Chỉ có thể đặt lại hàng cho đơn đã hủy.');
        }

        // Nếu đăng nhập: đồng bộ vào giỏ hàng DB; nếu chưa: session cart
        if (auth()->check()) {
            $cart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);
            foreach ($order->orderDetails as $detail) {
                \App\Models\CartItem::updateOrCreate(
                    [
                        'cart_id' => $cart->id,
                        'product_variant_id' => $detail->product_variant_id,
                    ],
                    [
                        'quantity' => \DB::raw('COALESCE(quantity,0) + ' . (int)$detail->quantity),
                        'price' => $detail->price,
                    ]
                );
            }
        } else {
            $sessionCart = session()->get('cart', []);
            foreach ($order->orderDetails as $detail) {
                $sessionCart[] = [
                    'product_variant_id' => $detail->product_variant_id,
                    'quantity' => (int)$detail->quantity,
                    'price' => $detail->price,
                ];
            }
            session()->put('cart', $sessionCart);
        }

        return redirect()->route('cart')->with('success', 'Đã thêm lại sản phẩm vào giỏ hàng.');
    }


    /**
     * Show the form for creating a new resource.
     */
}
