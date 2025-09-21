<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Product;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // hoặc số lượng bạn muốn mỗi trang
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
        ];

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('uploads/avatars'), $avatarName);
            $data['avatar'] = 'uploads/avatars/' . $avatarName;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();

        // Mapping role string to role_id
        $roleMap = [
            'admin' => 1,
            'user' => 2,
        ];
        $data['role_id'] = $roleMap[$data['role']] ?? null;

        // Nếu có mật khẩu mới thì mã hóa
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công.');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái người dùng đã được cập nhật.'
        ]);
    }
} 