<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm người dùng thành công',
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật người dùng thành công',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa người dùng thành công'
        ]);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'is_active' => $user->is_active
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (auth()->attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $user = auth()->user();
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác'
            ], 401);
        }
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công',
            'user' => $user
        ]);
    }

    public function storeSapProduct(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:100|unique:sap_products,product_code',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            // Thêm các rule khác nếu cần
        ]);

        // ...code xử lý lưu sản phẩm SAP...
    }
}
