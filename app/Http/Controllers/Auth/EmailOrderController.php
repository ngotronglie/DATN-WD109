<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;


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

        // Eager load chi tiết đơn để email admin hiển thị đầy đủ (nếu template cần)
        try {
            $order->load(['orderDetails.productVariant.product']);
        } catch (\Throwable $e) {
            \Log::info('Could not eager-load order relations for admin email: ' . $e->getMessage());
        }

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

        // Thông báo email tới Admin (role_id == 2) — gửi 1 email và BCC tất cả admin
        try {
            $admins = User::where('role_id', 2)->whereNotNull('email')->get();
            \Log::warning('[Order Admin Notify] Found admins count (DB): ' . $admins->count() . ' for order ' . $order->order_code);

            // Lấy thêm email từ ENV ADMIN_NOTIFICATION_EMAILS (phân tách bởi dấu phẩy)
            $envEmailsRaw = env('ADMIN_NOTIFICATION_EMAILS');
            $envEmails = [];
            if (!empty($envEmailsRaw)) {
                $envEmails = collect(explode(',', $envEmailsRaw))
                    ->map(function ($e) { return trim($e); })
                    ->filter()
                    ->all();
            }
            if (!empty($envEmails)) {
                \Log::warning('[Order Admin Notify] ENV emails provided: ' . implode(',', $envEmails));
            } else {
                \Log::warning('[Order Admin Notify] No ENV ADMIN_NOTIFICATION_EMAILS provided.');
            }

            // Hợp nhất email từ DB và ENV rồi loại trùng
            $emails = collect($admins->pluck('email')->all())
                ->merge($envEmails)
                ->filter()
                ->unique()
                ->values()
                ->all();

            if (empty($emails)) {
                \Log::warning('[Order Admin Notify] No recipient emails available after merging DB and ENV. Skipping send.');
            } else {
                $primary = $emails[0];
                $bccList = array_slice($emails, 1);

                \Log::warning('[Order Admin Notify] Primary to: ' . $primary . ' | BCC count: ' . count($bccList));
                if (!empty($bccList)) {
                    \Log::warning('[Order Admin Notify] BCC list: ' . implode(',', $bccList));
                }

                // Chọn 1 admin bất kỳ để truyền biến $admin cho view (ví dụ admin đầu tiên từ DB nếu có, nếu không tạo đối tượng tạm)
                $admin = $admins->first();
                if (!$admin) {
                    $admin = new \stdClass();
                    $admin->name = 'Admin';
                    $admin->email = $primary;
                }

                Mail::send('emails.order-admin-notify', compact('user', 'order', 'admin'), function ($message) use ($primary, $bccList, $order) {
                    $message->to($primary);
                    if (!empty($bccList)) {
                        $message->bcc($bccList);
                    }
                    $message->subject('Đơn hàng mới #' . $order->order_code);
                });

                \Log::warning('[Order Admin Notify] Completed SMTP send for order ' . $order->order_code);
            }
        } catch (\Throwable $e) {
            \Log::warning('SMTP send failed for admin notify, falling back to log mailer: ' . $e->getMessage());
            $admins = isset($admins) ? $admins : User::where('role_id', 2)->whereNotNull('email')->get();
            $envEmailsRaw = env('ADMIN_NOTIFICATION_EMAILS');
            $envEmails = [];
            if (!empty($envEmailsRaw)) {
                $envEmails = collect(explode(',', $envEmailsRaw))
                    ->map(function ($e) { return trim($e); })
                    ->filter()
                    ->all();
            }

            $emails = collect(optional($admins)->pluck('email')->all() ?? [])
                ->merge($envEmails)
                ->filter()
                ->unique()
                ->values()
                ->all();

            if (!empty($emails)) {
                $primary = $emails[0];
                $bccList = array_slice($emails, 1);

                \Log::warning('[Order Admin Notify - Fallback] Using log mailer. Primary: ' . $primary . ' | BCC count: ' . count($bccList));

                $admin = $admins && $admins->isNotEmpty() ? $admins->first() : (function() use ($primary) {
                    $o = new \stdClass(); $o->name = 'Admin'; $o->email = $primary; return $o; })();

                Mail::mailer('log')->send('emails.order-admin-notify', compact('user', 'order', 'admin'), function ($message) use ($primary, $bccList, $order) {
                    $message->to($primary);
                    if (!empty($bccList)) {
                        $message->bcc($bccList);
                    }
                    $message->subject('Đơn hàng mới #' . $order->order_code);
                });
            } else {
                \Log::warning('[Order Admin Notify - Fallback] No recipient emails available after merging DB and ENV.');
            }
        }

        // Xóa giỏ hàng sau khi tạo đơn hàng thành công
        try {
            if ($user) {
                $cart = Cart::where('user_id', $user->id)->first();
                if ($cart) {
                    $cart->items()->delete();
                    $cart->delete();
                }
            }
            // Xóa giỏ hàng session (phòng trường hợp dùng session)
            Session::forget('cart');
        } catch (\Throwable $e) {
            \Log::warning('Không thể xóa giỏ hàng sau khi đặt hàng (EmailOrderController): ' . $e->getMessage());
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
