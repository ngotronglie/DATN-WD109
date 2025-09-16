<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('mainVariant');

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('categories_id', $request->category);
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->whereHas('mainVariant', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('mainVariant', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Lọc theo màu sắc
        if ($request->filled('color')) {
            $query->whereHas('mainVariant', function($q) use ($request) {
                $q->where('color_id', $request->color);
            });
        }

        // Sắp xếp
        if ($request->sort == 'az') {
            $query->orderBy('name', 'asc');
        } elseif ($request->sort == 'za') {
            $query->orderBy('name', 'desc');
        }

        $products = $query->paginate(12)->appends($request->all());
        $allCategories = \App\Models\Categories::all();
        $colors = \App\Models\Color::all();

        return view('layouts.user.shop', compact('products', 'allCategories', 'colors'));
    }

    public function show($id)
    {
        $product = Product::with(['variants.color', 'variants.capacity', 'category'])->findOrFail($id);

        // Lấy danh sách màu sắc và dung lượng từ các variant
        $colors = $product->variants->map(function($variant) {
            return $variant->color;
        })->unique('id')->filter();

        $capacities = $product->variants->map(function($variant) {
            return $variant->capacity;
        })->unique('id')->filter();

        // Lấy tất cả danh mục cha (nếu cần cho sidebar)
        $categories = \App\Models\Categories::with('children')->whereNull('Parent_id')->get();

        $variants = $product->variants;

        // Lấy bình luận như ở ClientController@productDetail
        $comments = $product->comments()
            ->with('user', 'replies.user')
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('layouts.user.productDetail', compact('product', 'colors', 'capacities', 'categories', 'variants', 'comments'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // ... validate các trường khác
        Product::create($data);
        // ...
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->all();
        $product->update($data);
        // ...
    }
}
