<?php

namespace App\Http\Controllers\Admin;

use App\Models\Voucher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Hiển thị danh sách voucher.
     */
    public function index()
    {
        $vouchers = Voucher::paginate(10);
        return view('layouts.admin.voucher.list', compact('vouchers'));
    }

    /**
     * Hiển thị form tạo voucher mới.
     */
    public function create()
    {
        return view('layouts.admin.voucher.create');
    }

    /**
     * Lưu voucher mới vào database.
     */
    public function store(Request $request)
    {
        // Chuyển checkbox is_active sang boolean 1 hoặc 0
        $request->merge(['is_active' => $request->has('is_active')]);

        $validatedData = $request->validate([
            'code' => 'required|unique:vouchers',
            'discount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer',
            'min_money' => 'required|numeric',
            'max_money' => 'required|numeric|gte:min_money',
            'is_active' => 'required|boolean',
        ]);

        Voucher::create($validatedData);

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher đã được thêm thành công!');
    }


    /**
     * Hiển thị chi tiết một voucher (nếu cần).
     */
    public function show(Voucher $voucher) {}

    /**
     * Hiển thị form chỉnh sửa voucher.
     */
    public function edit(Voucher $voucher)
    {
        return view('layouts.admin.voucher.update', compact('voucher'));
    }

    /**
     * Cập nhật thông tin voucher.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer',
            'min_money' => 'required|numeric',
            'max_money' => 'required|numeric|gte:min_money',
            'is_active' => 'nullable|boolean'
        ]);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $voucher->update($data);


        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher đã được cập nhật thành công!');
    }

    /**
     * Xóa một voucher.
     */
public function destroy(string $id)
{
    $voucher = Voucher::find($id);

    if (!$voucher) {
        return redirect()->route('admin.vouchers.index')->with('error', 'Voucher không tồn tại.');
    }

    $voucher->delete();

    return redirect()->route('admin.vouchers.index')->with('success', 'Voucher đã được xóa thành công!');
}

}
