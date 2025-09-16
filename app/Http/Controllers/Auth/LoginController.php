<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
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
        return view('auth.login'); // đổi 'Auth.login' thành chữ thường nếu theo convention
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            // Redirect to provided path if present (only allow relative paths)
            $redirect = $request->input('redirect');
            if ($redirect && is_string($redirect) && str_starts_with($redirect, '/')) {
                return redirect($redirect)->with('success', 'Đăng nhập thành công!');
            }
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        return back()->with('error', 'Thông tin đăng nhập không đúng.');
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

    public function logout(Request $request)
    {
        // Đăng xuất người dùng
        Auth::logout();

        // Hủy session hiện tại để bảo mật
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Chuyển hướng người dùng về trang chủ hoặc trang đăng nhập
        return redirect()->route('home')->with('success', 'Đăng xuất thành công!');
    }
}