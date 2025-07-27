<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class FlashSaleItemController extends Controller
{
    /**
     * Hiển thị danh sách các sản phẩm có trong Flash Sale.
     */
    public function index(FlashSale $flashsale)
    {
        $items = $flashsale->items()->with('productVariant')->paginate(10);
        return view('admin.flashsaleitems.index', compact('flashsale', 'items'));
    }

    /**
     * Hiển thị form thêm sản phẩm vào Flash Sale.
     */
    public function create(FlashSale $flashsale)
    {
        $productVariants = ProductVariant::all(); // Danh sách tất cả các sản phẩm
        return view('admin.flashsaleitems.create', compact('flashsale', 'productVariants'));
    }

    /**
     * Lưu sản phẩm vào Flash Sale.
     */
    public function store(Request $request, FlashSale $flashsale)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'flash_price' => 'required|numeric|min:0',
            'quantity_limit' => 'required|integer|min:0',
        ]);

        $flashsale->items()->create([
            'product_variant_id' => $validated['product_variant_id'],
            'flash_price' => $validated['flash_price'],
            'quantity_limit' => $validated['quantity_limit'],
        ]);

        return redirect()->route('flashsales.items.index', $flashsale->id)->with('success', 'Sản phẩm đã được thêm vào Flash Sale!');
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm trong Flash Sale.
     */
    public function edit(FlashSale $flashsale, FlashSaleItem $item)
    {
        return view('admin.flashsaleitems.edit', compact('flashsale', 'item'));
    }

    /**
     * Cập nhật sản phẩm trong Flash Sale.
     */
    public function update(Request $request, FlashSale $flashsale, FlashSaleItem $item)
    {
        $validated = $request->validate([
            'flash_price' => 'required|numeric|min:0',
            'quantity_limit' => 'required|integer|min:0',
        ]);

        $item->update($validated);

        return redirect()->route('flashsales.items.index', $flashsale->id)->with('success', 'Sản phẩm trong Flash Sale đã được cập nhật!');
    }

    /**
     * Xóa sản phẩm khỏi Flash Sale.
     */
    public function destroy(FlashSale $flashsale, FlashSaleItem $item)
    {
        $item->delete();

        return redirect()->route('flashsales.items.index', $flashsale->id)->with('success', 'Sản phẩm đã được xóa khỏi Flash Sale!');
    }
}
