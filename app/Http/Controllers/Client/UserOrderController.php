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
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
        }

        $orders = Order::where('user_id', $user->id)->orderByDesc('created_at')->get();

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
            'bank_name' => 'required|string|max:255',
            'bank_number' => 'required|string|max:255',
            'reason' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('refund_images', 'public');
        }

        RefundRequest::create([
            'order_id' => $request->order_id,
            'user_id' => Auth::id(),
            'bank_name' => $request->bank_name,
            'bank_number' => $request->bank_number,
            'reason' => $request->reason,
            'image' => $imagePath,
            'refund_requested_at' => now(),
        ]);

        return back()->with('success', 'Đã gửi yêu cầu hoàn tiền.');
    }
    public function returnOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_number' => 'required|string|max:50',
            'reason' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('refund_images', 'public');
        }

        RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'bank_name' => $request->bank_name,
            'bank_number' => $request->bank_number,
            'reason' => $request->reason,
            'image' => $imagePath,
            'refund_requested_at' => now(),
        ]);

        $order->status = 7; // 7 là mã trạng thái: "Đã yêu cầu hoàn"
        $order->save();

        return redirect()->route('account.order')->with('success', 'Đã gửi yêu cầu hoàn hàng.');
    }
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->status !== 0) {
            return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng đang chờ xác nhận.');
        }

        $order->status = 6; // Giả sử 6 là trạng thái "Đã hủy"
        $order->save();

        return redirect()->route('account.order')->with('success', 'Đơn hàng đã được hủy thành công.');
    }


    /**
     * Show the form for creating a new resource.
     */
}
