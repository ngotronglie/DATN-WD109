<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate token
        $token = Str::random(64);

        // Save to password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Gửi mail
        Mail::send('emails.reset-password', ['token' => $token], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Khôi phục mật khẩu');
        });

        return back()->with('success', 'Đã gửi link đặt lại mật khẩu tới email của bạn.');
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn.']);
        }

        // Cập nhật mật khẩu
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Xoá token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('auth.login')->with('success', 'Đặt lại mật khẩu thành công. Hãy đăng nhập.');
    }
}
