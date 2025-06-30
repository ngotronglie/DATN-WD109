<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class VerifyEmailController extends Controller
{
    public function verify($email, $token)
    {
        $cachedToken = Cache::get('verify_' . $email);

        if (!$cachedToken || $cachedToken !== $token) {
            return redirect()->route('auth.login')->with('error', 'Liên kết xác minh không hợp lệ hoặc đã hết hạn.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Người dùng không tồn tại.');
        }

        // Đánh dấu xác minh email
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Xoá token khỏi cache
        Cache::forget('verify_' . $email);

        return redirect()->route('auth.login')->with('success', 'Xác minh email thành công. Bạn có thể đăng nhập.');
    }
}
