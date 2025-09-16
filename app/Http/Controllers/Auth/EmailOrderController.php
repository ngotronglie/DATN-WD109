<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderDetail;
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

        // Gửi mail (có fallback sang log nếu SMTP lỗi)
        try {
            Mail::send('emails.order-success', compact('user', 'order'), function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Đặt hàng thành công');
            });
        } catch (\Throwable $e) {
            \Log::warning('SMTP send failed in placeOrder, falling back to log mailer: ' . $e->getMessage());
            Mail::mailer('log')->send('emails.order-success', compact('user', 'order'), function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Đặt hàng thành công');
            });
        }

        return redirect()->route('order.success')->with('success', 'Đặt hàng thành công!');
    }

    public function cancelOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = auth()->user();

        // Kiểm tra trạng thái hiện tại để tránh hủy những đơn đã giao
        if ($order->status == 0 || $order->status == 1) {
            $order->status = 6; // Đã hủy
            $order->save();

            // Gửi mail hủy đơn (có fallback sang log nếu SMTP lỗi)
            try {
                Mail::send('emails.order-cancelled', compact('user', 'order'), function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Đơn hàng đã bị hủy');
                });
            } catch (\Throwable $e) {
                \Log::warning('SMTP send failed in cancelOrder, falling back to log mailer: ' . $e->getMessage());
                Mail::mailer('log')->send('emails.order-cancelled', compact('user', 'order'), function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Đơn hàng đã bị hủy');
                });
            }

            return back()->with('success', 'Đã hủy đơn hàng.');
        }

        return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
    }
}
