<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class FlashSaleProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm trong Flash Sale.
     */
    public function index()
    {
        $flashSaleProducts = FlashSaleProduct::with(['flashSale', 'productVariant'])->get();

        return view('admin.flash_sale_products.index', compact('flashSaleProducts'));
    }

    /**
     * Hiển thị form thêm sản phẩm vào Flash Sale.
     */
    public function create()
    {
        $flashSales = FlashSale::all();
        $productVariants = ProductVariant::with(['product', 'color', 'capacity'])->get();

        return view('admin.flash_sale_products.create', compact('flashSales', 'productVariants'));
    }

    /**
     * Thêm sản phẩm vào Flash Sale.
     */
    public function store(Request $request)
    {
        $request->validate([
            'flash_sale_id' => 'required|exists:flash_sales,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|lt:original_price',
            'initial_stock' => 'required|integer|min:0',
            'remaining_stock' => 'required|integer|min:0',
        ]);

        FlashSaleProduct::create($request->all());

        return redirect()->route('admin.flash_sale_products.index')
            ->with('success', 'Sản phẩm được thêm vào Flash Sale thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm trong Flash Sale.
     */
    public function edit($id)
    {
        $flashSaleProduct = FlashSaleProduct::findOrFail($id);
        $flashSales = FlashSale::all();
        $productVariants = ProductVariant::with(['product', 'color', 'capacity'])->get();

        return view('admin.flash_sale_products.edit', compact('flashSaleProduct', 'flashSales', 'productVariants'));
    }

    /**
     * Cập nhật sản phẩm trong Flash Sale.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'flash_sale_id' => 'required|exists:flash_sales,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|lt:original_price',
            'initial_stock' => 'required|integer|min:0',
            'remaining_stock' => 'required|integer|min:0',
        ]);

        $flashSaleProduct = FlashSaleProduct::findOrFail($id);
        $flashSaleProduct->update($request->all());

        return redirect()->route('admin.flash_sale_products.index')
            ->with('success', 'Sản phẩm trong Flash Sale đã được cập nhật.');
    }

    /**
     * Xóa sản phẩm khỏi Flash Sale.
     */
    public function destroy($id)
    {
        $flashSaleProduct = FlashSaleProduct::findOrFail($id);
        $flashSaleProduct->delete();

        return redirect()->route('admin.flash_sale_products.index')
            ->with('success', 'Sản phẩm đã được xóa khỏi Flash Sale.');
    }
}
