<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('product')->latest()->paginate(10);
        return view('layouts.admin.flashsale.index', compact('flashSales'));
    }

    public function create()
    {
        $products = Product::all(['id', 'name']);
        return view('layouts.admin.flashsale.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
            'sale_price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $validated['original_price'] = $product->price;

            FlashSale::create($validated);

            return redirect()->route('admin.flash-sales.index')
                ->with('success', 'Thêm mới Flash Sale thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi thêm Flash Sale')
                ->withInput();
        }
    }

    // ... các method khác
}
