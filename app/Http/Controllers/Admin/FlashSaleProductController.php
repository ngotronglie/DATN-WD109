<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm của Flash Sale.
     */
    public function index()
    {
        $flashSaleProducts = FlashSaleProduct::with(['flashSale', 'product'])->get();
        return view('admin.flash_sale_products.index', compact('flashSaleProducts'));
    }

    /**
     * Hiển thị form thêm sản phẩm vào Flash Sale.
     */
    public function create()
    {
        $flashSales = FlashSale::all();
        $products = Product::all();
        return view('admin.flash_sale_products.create', compact('flashSales', 'products'));
    }

    /**
     * Lưu sản phẩm mới vào Flash Sale.
     */
    public function store(Request $request)
    {
        $request->validate([
            'flash_sale_id' => 'required|exists:flash_sales,id',
            'product_id' => 'required|exists:products,id',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|lt:original_price',
            'initial_stock' => 'required|integer|min:0',
            'remaining_stock' => 'required|integer|min:0',
        ]);

        FlashSaleProduct::create($request->all());

        return redirect()->route('admin.flash_sale_products.index')
            ->with('success', 'Thêm sản phẩm vào Flash Sale thành công.');
    }

    /**
     * Hiển thị chi tiết một sản phẩm trong Flash Sale.
     */
    public function show($id)
    {
        $flashSaleProduct = FlashSaleProduct::with(['flashSale', 'product'])->findOrFail($id);
        return view('admin.flash_sale_products.show', compact('flashSaleProduct'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm trong Flash Sale.
     */
    public function edit($id)
    {
        $flashSaleProduct = FlashSaleProduct::findOrFail($id);
        $flashSales = FlashSale::all();
        $products = Product::all();

        return view('admin.flash_sale_products.edit', compact('flashSaleProduct', 'flashSales', 'products'));
    }

    /**
     * Cập nhật thông tin sản phẩm trong Flash Sale.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'flash_sale_id' => 'required|exists:flash_sales,id',
            'product_id' => 'required|exists:products,id',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|lt:original_price',
            'initial_stock' => 'required|integer|min:0',
            'remaining_stock' => 'required|integer|min:0',
        ]);

        $flashSaleProduct = FlashSaleProduct::findOrFail($id);
        $flashSaleProduct->update($request->all());

        return redirect()->route('admin.flash_sale_products.index')
            ->with('success', 'Cập nhật sản phẩm trong Flash Sale thành công.');
    }

    /**
     * Xóa một sản phẩm khỏi Flash Sale.
     */
    public function destroy($id)
    {
        $flashSaleProduct = FlashSaleProduct::findOrFail($id);
        $flashSaleProduct->delete();

        return redirect()->route('admin.flash_sale_products.index')
            ->with('success', 'Xóa sản phẩm khỏi Flash Sale thành công.');
    }
}
