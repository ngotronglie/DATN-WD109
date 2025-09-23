<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{


    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('account.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->date_of_birth = $request->date_of_birth;

        // Nếu có ảnh mới
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatar;
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
public function changePassword()
{
    return view('account.change-password');
}

// Xử lý cập nhật mật khẩu
public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = Auth::user();

    // Kiểm tra mật khẩu cũ
    if (!\Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
    }

    // Cập nhật mật khẩu mới
    $user->password = \Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Đổi mật khẩu thành công!');
}
}
