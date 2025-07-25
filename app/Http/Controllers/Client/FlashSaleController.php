<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $activeFlashSales = FlashSale::with('product')
            ->active()
            ->whereHas('product')
            ->get();

        return view('client.flash-sales.index', compact('activeFlashSales'));
    }

    public function show($id)
    {
        $flashSale = FlashSale::with('product')->findOrFail($id);

        if (!$flashSale->isActive()) {
            return redirect()->route('client.flash-sales.index')
                ->with('error', 'Flash sale này không còn hoạt động');
        }

        return view('client.flash-sales.show', compact('flashSale'));
    }
}
