<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $activeFlashSales = FlashSale::with(['product', 'product.variants'])
            ->active()
            ->whereHas('product', function($query) {
                $query->where('is_active', 1);
            })
            ->get();

        return view('layouts.user.flash-sales.index', compact('activeFlashSales'));
        // Sửa path view theo cấu trúc hiện tại của dự án
    }

    public function show($id)
    {
        $flashSale = FlashSale::with(['product', 'product.variants'])->findOrFail($id);

        if (!$flashSale->isActive() || !$flashSale->product->is_active) {
            return redirect()->route('client.flash-sales.index')
                ->with('error', 'Flash sale này không còn hoạt động');
        }

        return view('layouts.user.flash-sales.show', compact('flashSale'));
    }
}
