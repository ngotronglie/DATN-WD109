<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.products.index')->with('info', 'Product variants are managed within the product editor.');
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.products.index')->with('info', 'Create variants inside a product.');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('admin.products.index')->with('info', 'Use the product page to add variants.');
    }

    public function show($id): RedirectResponse
    {
        return redirect()->route('admin.products.index');
    }

    public function edit($id): RedirectResponse
    {
        return redirect()->route('admin.products.index');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        return redirect()->route('admin.products.index');
    }

    public function destroy($id): RedirectResponse
    {
        return redirect()->route('admin.products.index');
    }
}
