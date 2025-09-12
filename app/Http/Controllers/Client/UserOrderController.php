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
            'reason' => 'required|string',
            'reason_input' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

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

        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
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
            'account_name' => $request->account_name,
            'reason' => $request->reason,
            'image' => $imagePath,
            'refund_requested_at' => now(),
        ]);

        $order->status = 7; // Đã yêu cầu hoàn
        $order->save();

        return redirect()->route('account.order')->with('success', 'Đã gửi yêu cầu hoàn hàng.');
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

        return redirect()->route('user.orders.show', $refund->order_id)->with('success', 'Thông tin hoàn hàng đã được cập nhật.');
    }


    /**
     * Show the form for creating a new resource.
     */
}
