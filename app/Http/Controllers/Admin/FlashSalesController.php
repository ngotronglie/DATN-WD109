<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale; // Sử dụng model đã có
use Illuminate\Http\Request;

class FlashSalesController extends Controller
{
    /**
     * Hiển thị danh sách các chương trình Flash Sale.
     */
    public function index()
    {
        $flashSales = FlashSale::all();
        return view('layouts.admin.flash_sales.index', compact('flashSales')); // Đường dẫn đã được sửa đúng
    }

    /**
     * Hiển thị form tạo mới.
     */
    public function create()
    {
        return view('admin.flash_sales.create'); // Trả về view tạo mới
    }

    /**
     * Lưu thông tin Flash Sale mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:scheduled,active,inactive',
        ]);

        // Tạo mới Flash Sale
        FlashSale::create($request->all());

        return redirect()->route('admin.flash_sales.index')
            ->with('success', 'Tạo Flash Sale thành công.');
    }

    /**
     * Hiển thị chi tiết một Flash Sale.
     */
    public function show($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        return view('admin.flash_sales.show', compact('flashSale'));
    }

    /**
     * Hiển thị form chỉnh sửa Flash Sale.
     */
    public function edit($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        return view('admin.flash_sales.edit', compact('flashSale'));
    }

    /**
     * Cập nhật Flash Sale trong cơ sở dữ liệu.
     */
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:scheduled,active,inactive',
        ]);

        // Cập nhật Flash Sale
        $flashSale = FlashSale::findOrFail($id);
        $flashSale->update($request->all());

        return redirect()->route('admin.flash_sales.index')
            ->with('success', 'Cập nhật Flash Sale thành công.');
    }

    /**
     * Xóa một Flash Sale.
     */
    public function destroy($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $flashSale->delete();

        return redirect()->route('admin.flash_sales.index')
            ->with('success', 'Xóa Flash Sale thành công.');
    }
}
