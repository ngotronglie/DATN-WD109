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

    // ... các method khác
}
