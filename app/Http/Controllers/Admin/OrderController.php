<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Order::with(['voucher', 'orderDetails.productVariant.color', 'orderDetails.productVariant.capacity', 'orderDetails.productVariant.product'])
            ->orderByDesc('created_at');

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
        $order->status = (int) $request->input('status');
        $order->save();
        return response()->json(['success' => true]);
    }
}

