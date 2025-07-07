<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class EmailOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function placeOrder(Request $request)
{
    $user = auth()->user();

    $order = Order::create([
        'user_id'         => $user->id,
        'price'           => $request->price ?? 0,
        'name'            => $request->name,
        'address'         => $request->address,
        'email'           => $request->email,
        'phone'           => $request->phone,
        'note'            => $request->note,
        'total_amount'    => $request->total_amount ?? 0,
        'status'          => 'pending',
        'payment_method'  => 'vnpay',
        'order_code'      => Str::upper(Str::random(10)), // ✅ sửa lại ở đây
        'voucher_id'      => $request->voucher_id ?? null,
        'status_method'   => 'Chờ xử lý',
    ]);

    // Gửi mail
    Mail::send('emails.order-success', compact('user', 'order'), function ($message) use ($user) {
        $message->to($user->email);
        $message->subject('Đặt hàng thành công');
    });

    return redirect()->route('order.success')->with('success', 'Đặt hàng thành công!');
}

    public function cancelOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = auth()->user();

        $order->status = 'cancelled';
        $order->save();

        // Gửi mail hủy đơn
        Mail::send('emails.order-cancelled', compact('user', 'order'), function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Đơn hàng đã bị hủy');
        });

        return back()->with('success', 'Đã hủy đơn hàng.');
    }
}
