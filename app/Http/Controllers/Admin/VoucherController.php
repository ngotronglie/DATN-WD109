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
        $vouchers = Voucher::paginate(5);
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
        // Chuyển checkbox về boolean
        $request->merge(['is_active' => $request->has('is_active')]);

        // Validation
        $validatedData = $request->validate([
            'code' => 'required|unique:vouchers',
            'discount' => 'required|numeric|gt:0',
            'start_date' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer|gt:0',
            'min_money' => 'required|numeric|gt:0',
            'max_money' => 'required|numeric|gt:0|gte:min_money',
            'is_active' => 'required|boolean',
        ], [
            'discount.gt' => 'Mã giảm giá phải lớn hơn 0.',
            'quantity.gt' => 'Số lượng phải lớn hơn 0.',
            'min_money.gt' => 'Số tiền tối thiểu phải lớn hơn 0.',
            'max_money.gt' => 'Số tiền tối đa phải lớn hơn 0.',
            'max_money.gte' => 'Số tiền tối đa phải lớn hơn hoặc bằng số tiền tối thiểu.',
        ]);

        try {
            $voucher = Voucher::create($validatedData);

            if ($voucher) {
                return redirect()->route('admin.vouchers.index')->with('success', 'Voucher đã được tạo thành công!');
            } else {
                return redirect()->back()->with('error', 'Không thể tạo voucher. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi tạo voucher: ' . $e->getMessage());
        }
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
        $request->merge(['is_active' => $request->has('is_active')]);

        $validatedData = $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount' => 'required|numeric|gt:0',
            'start_date' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer|gt:0',
            'min_money' => 'required|numeric|gt:0',
            'max_money' => 'required|numeric|gt:0|gte:min_money',
            'is_active' => 'required|boolean'
        ], [
            'discount.gt' => 'Mã giảm giá phải lớn hơn 0.',
            'quantity.gt' => 'Số lượng phải lớn hơn 0.',
            'min_money.gt' => 'Số tiền tối thiểu phải lớn hơn 0.',
            'max_money.gt' => 'Số tiền tối đa phải lớn hơn 0.',
            'max_money.gte' => 'Số tiền tối đa phải lớn hơn hoặc bằng số tiền tối thiểu.',
        ]);

        try {
            $updated = $voucher->update($validatedData);

            if ($updated) {
                return redirect()->route('admin.vouchers.index')->with('success', 'Voucher đã được cập nhật thành công!');
            } else {
                return redirect()->back()->with('error', 'Không thể cập nhật voucher. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật voucher: ' . $e->getMessage());
        }
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
