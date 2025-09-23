<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function confirmReceiveBack($id)
    {
        $refund = RefundRequest::findOrFail($id);
        $refund->received_back_at = now();
        $refund->save();

        $order = Order::find($refund->order_id); // đảm bảo RefundRequest có order_id
        if ($order) {
            $order->status = 7; // hoặc status khác phù hợp với logic bạn đặt
            $order->save();
        }
        return back()->with('success', 'Đã xác nhận nhận lại hàng từ khách.');
    }
}
