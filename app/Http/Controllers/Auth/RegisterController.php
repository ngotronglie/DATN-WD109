<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'role' => ['required', Rule::in(['user'])], // không cần nếu mặc định là user
            'address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1, // gán trực tiếp nếu luôn là user
            'is_active' => true,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'avatar' => null
        ]);
        $token = Str::random(64);
        Cache::put('verify_' . $user->email, $token, now()->addMinutes(60)); // Lưu token trong cache 60 phút

        try {
            // Gửi bằng mailer cấu hình mặc định (SMTP, etc.). Nếu lỗi sẽ fallback sang log mailer
            Mail::send('emails.verify-email', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Xác minh địa chỉ email');
            });
        } catch (\Throwable $e) {
            \Log::warning('Gửi mail xác minh thất bại, fallback sang log mailer: ' . $e->getMessage());
            try {
                Mail::mailer('log')->send('emails.verify-email', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Xác minh địa chỉ email');
                });
            } catch (\Throwable $e2) {
                \Log::error('Fallback log mailer cũng thất bại: ' . $e2->getMessage());
            }
        }

        Auth::login($user);
        return view('auth.verify-notice', ['user' => $user]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
