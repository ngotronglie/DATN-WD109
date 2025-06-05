<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class voucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::paginate(10);
        return resource()->json($vouchers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['is_active' => $request->has('is_active') ? 1 : 0]);

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

        $voucher = Voucher::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Voucher đã được thêm thành công!',
            'voucher' => $voucher,
        ]);
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
    public function update(Request $request, Voucher $voucher)
    {
        $request->merge(['is_active' => $request->has('is_active') ? 1 : 0]);

        $validatedData = $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer',
            'min_money' => 'required|numeric',
            'max_money' => 'required|numeric|gte:min_money',
            'is_active' => 'required|boolean',
        ]);

        $voucher->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Voucher đã được cập nhật thành công!',
            'voucher' => $voucher,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $voucher = Voucher::find($id);

    if (!$voucher) {
        return response()->json(['message' => 'Voucher không tồn tại.'], 404);
    }

    $voucher->delete();

    return response()->json(['message' => 'Voucher đã được xóa thành công!']);
}

}
