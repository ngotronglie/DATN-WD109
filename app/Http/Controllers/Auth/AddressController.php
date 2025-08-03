<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{

    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();

        return view('account.address_list', compact('addresses'));
    }
    public function create()
    {
        $provinces = DB::table('tinhthanh')->get(['id', 'ten_tinh']);
        return view('account.address', compact('provinces'));
    }

    public function getWards($provinceId)
    {
        $wards = DB::table('phuong_xa')
            ->where('ma_tinh', $provinceId)
            ->get(['id', 'ten_phuong_xa']);

        return response()->json($wards);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'city' => 'required|string',
        ]);

        if ($request->has('is_default')) {
            // reset địa chỉ mặc định cũ
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => auth()->id(),
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'district' => $request->district,
            'street' => $request->street,
            'ward' => $request->ward,
            'city' => $request->city,
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('account.address_list')->with('success', 'Thêm địa chỉ thành công!');
    }


    public function edit($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        return view('account.address_edit', compact('address'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string',
            'ward' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
        ]);

        $address = Address::where('user_id', auth()->id())->findOrFail($id);

        if ($request->has('is_default')) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
            $address->is_default = true;
        } else {
            $address->is_default = false;
        }

        $address->update([
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'street' => $request->street,
            'ward' => $request->ward,
            'district' => $request->district,
            'city' => $request->city,
            'is_default' => $address->is_default,
        ]);

        return redirect()->route('account.address_list')->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function delete($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();

        return redirect()->route('account.address_list')->with('success', 'Xóa địa chỉ thành công!');
    }
    public function setDefault($id)
    {
        $userId = auth()->id();

        // Hủy mặc định tất cả địa chỉ hiện tại
        Address::where('user_id', $userId)->update(['is_default' => false]);

        // Đặt địa chỉ được chọn làm mặc định
        Address::where('user_id', $userId)->where('id', $id)->update(['is_default' => true]);

        return redirect()->route('account.address_list')->with('success', 'Đã đặt địa chỉ mặc định!');
    }
}
