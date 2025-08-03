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
        $order = Order::findOrFail($id);
        $newStatus = $request->input('status');

        $order->status = $newStatus;

        // Nếu đơn hàng được đánh dấu là "Đã giao hàng" (status = 5)
        if ((int)$newStatus === 5) {
            $order->status_method = 1;
            $order->payment_method = 'cod';
        }

        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
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

        // ✅ Cập nhật trạng thái đơn hàng
        $order = Order::find($refund->order_id);
        if ($order && $order->status == 8) {
            $order->status = 9; // Trạng thái "Đã hoàn"
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

        // Cập nhật trạng thái đơn hàng thành 10
        $order = Order::find($refund->order_id);
        if ($order) {
            $order->status = 10; // Hoàn hàng bị từ chối
            $order->save();
        }

        return redirect()->back()->with('success', 'Yêu cầu hoàn đã bị từ chối.');
    }
    public function confirmReceiveBack($refundId)
    {
        $refund = RefundRequest::findOrFail($refundId);
        $order = $refund->order;

        // Kiểm tra trạng thái hiện tại trước khi cập nhật
        if ($order->status != 7) {
            return back()->with('error', 'Đơn hàng không ở trạng thái chờ xác nhận hoàn hàng.');
        }

        // Cập nhật thời gian đã nhận hàng hoàn
        $refund->received_back_at = now();
        $refund->save();

        // Cập nhật trạng thái đơn hàng sang "Đã nhận hàng hoàn"
        $order->status = 8;
        $order->save();

        return back()->with('success', 'Xác nhận đã nhận hàng hoàn thành công.');
    }
}
