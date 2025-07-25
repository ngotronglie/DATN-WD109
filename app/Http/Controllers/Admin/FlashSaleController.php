<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('product')->latest()->get();
        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sale_price' => 'required|numeric|min:0',
            'sale_quantity' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Kiểm tra xem sản phẩm đã có trong flash sale chưa
        $existingFlashSale = FlashSale::where('product_id', $request->product_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->first();

        if ($existingFlashSale) {
            return back()->withErrors(['message' => 'Sản phẩm này đã có trong flash sale trong khoảng thời gian đã chọn']);
        }

        FlashSale::create([
            'product_id' => $request->product_id,
            'sale_price' => $request->sale_price,
            'sale_quantity' => $request->sale_quantity,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'trang_thai' => 'kích_hoạt',
        ]);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale đã được tạo thành công');
    }

    public function edit(FlashSale $flashSale)
    {
        $products = Product::all();
        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'sale_price' => 'required|numeric|min:0',
            'sale_quantity' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'trang_thai' => 'required|in:kích_hoạt,vô_hiệu_hóa'
        ]);

        // Kiểm tra trùng lặp flash sale (loại trừ flash sale hiện tại)
        $existingFlashSale = FlashSale::where('product_id', $request->product_id)
            ->where('id', '!=', $flashSale->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })->first();

        if ($existingFlashSale) {
            return back()->withErrors(['message' => 'Sản phẩm này đã có trong flash sale trong khoảng thời gian đã chọn']);
        }

        $flashSale->update($request->all());

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale đã được cập nhật thành công');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash Sale đã được xóa thành công');
    }
}
