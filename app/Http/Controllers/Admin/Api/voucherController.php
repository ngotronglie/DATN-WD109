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
        return response()->json($vouchers);
    }

    /**
     * Tạo voucher mới (API không cần trả về view form).
     */
    public function create()
    {
        // API thường không có view, nên có thể bỏ hoặc trả về cấu trúc mặc định nếu cần
        return response()->json(['message' => 'API không hỗ trợ trả về form tạo voucher']);
    }

    /**
     * Lưu voucher mới vào database.
     */
    public function store(Request $request)
    {
        $request->merge(['is_active' => $request->has('is_active')]);

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
            return response()->json([
                'message' => 'Voucher đã được tạo thành công!',
                'voucher' => $voucher
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi tạo voucher.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết một voucher.
     */
    public function show(Voucher $voucher)
    {
        return response()->json($voucher);
    }

    /**
     * Hiển thị form chỉnh sửa voucher (API không cần).
     */
    public function edit(Voucher $voucher)
    {
        return response()->json(['message' => 'API không hỗ trợ trả về form chỉnh sửa voucher']);
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
            $voucher->update($validatedData);
            return response()->json([
                'message' => 'Voucher đã được cập nhật thành công!',
                'voucher' => $voucher
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi cập nhật voucher.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa một voucher.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json(['message' => 'Voucher không tồn tại.'], 404);
        }

        try {
            $voucher->delete();
            return response()->json(['message' => 'Voucher đã được xóa thành công!']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi xóa voucher.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
