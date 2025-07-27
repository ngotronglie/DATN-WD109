<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FlashSaleController extends Controller
{
    /**
     * Hiển thị danh sách Flash Sale.
     */
    public function index()
    {
        $flashSales = \App\Models\FlashSale::paginate(10);
        return view('admin.flashsales.index', compact('flashSales'));
    }

    /**
     * Hiển thị form để tạo mới Flash Sale.
     */
    public function create()
    {
        // Lấy tất cả sản phẩm (chỉ hiển thị một lần duy nhất)
        $products = \App\Models\Product::select('id', 'name')->get();
//        $products = \App\Models\Product::with('variants')->get();
        $products = \App\Models\Product::with(['variants.color', 'variants.capacity'])->get();



        return view('admin.flashsales.create', compact('products'));
    }

    /**
     * Lưu Flash Sale vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'required|boolean',
            'product_id' => 'required|exists:products,id', // Kiểm tra sản phẩm tồn tại
        ]);


        FlashSale::create($validated);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash Sale đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết Flash Sale.
     */
    public function show(FlashSale $flashsale)
    {
        return view('admin.flashsales.show', compact('flashsale'));
    }

    /**
     * Hiển thị form để chỉnh sửa Flash Sale.
     */
    public function edit(FlashSale $flashsale)
    {
        return view('admin.flashsales.edit', compact('flashsale'));
    }

    /**
     * Cập nhật Flash Sale.
     */
    public function update(Request $request, FlashSale $flashsale)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'required|boolean',
        ]);

        $flashsale->update($validated);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash Sale đã được cập nhật!');
    }

    /**
     * Xóa Flash Sale.
     */
    public function destroy(FlashSale $flashsale)
    {
        $flashsale->delete();

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash Sale đã được xóa!');
    }
}
Route::resource('flash-sales', \App\Http\Controllers\Admin\FlashSaleController::class);
