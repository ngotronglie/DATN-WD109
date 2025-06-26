<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Color;
use App\Models\Capacity;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $products = DB::select('SELECT
            p.id AS product_id,
            p.name AS product_name,
            p.view_count AS product_view,
            p.slug as product_slug,
            pv.image AS product_image,
            pv.price AS product_price,
            pv.price_sale AS product_price_discount
        FROM products p
        JOIN (
            SELECT *
            FROM product_variants
            WHERE (product_id, id) IN (
                SELECT product_id, MIN(id)
                FROM (
                    SELECT product_id, id
                    FROM product_variants
                    WHERE quantity > 0
                    ORDER BY RAND()
                ) AS random_variants
                GROUP BY product_id
            )
        ) pv ON pv.product_id = p.id
        WHERE p.is_active = 1
        LIMIT 0, 8;');
        return view('layouts.user.main', compact('products'));
    }

    public function products()
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $products = Product::where('is_active', 1)->paginate(12);

        return view('client.products');
    }

    public function category($slug)
    {
        $category = Categories::where('slug', $slug)->firstOrFail();
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $products = Product::where('is_active', 1)
            ->where('category_id', $category->id)
            ->paginate(12);

        return view('client.category');
    }

    public function product($slug)
    {
        return view('layouts.user.productDetail');
    }

    public function about()
    {
        return view('client.about');
    }

    public function contact()
    {
        return view('client.contact');
    }

    public function blog()
    {
        return view('client.blog');
    }

    public function post($slug)
    {

        return view('client.post');
    }
    public function search(Request $request)
    {
        return view('client.search');
    }

    public function productDetail($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        if (!request()->ajax() && request()->header('Purpose') !== 'prefetch') {
            $product->increment('view_count');
        }
        $variants = $product->variants()->where('quantity', '>', 0)->get();
        $colors = Color::all();
        $capacities = Capacity::all();
        $categories = \App\Models\Categories::with('children')->whereNull('Parent_id')->get();
        return view('layouts.user.productDetail', compact('product', 'variants', 'colors', 'capacities', 'categories'));
    }

    public function getVariant(Request $request)
    {
        $product_id = $request->input('product_id');
        $color_id = $request->input('color_id');
        $capacity_id = $request->input('capacity_id');

        $variant = ProductVariant::where('product_id', $product_id)
            ->where('color_id', $color_id)
            ->where('capacity_id', $capacity_id)
            ->first();

        if ($variant) {
            $product = $variant->product;
            $category = $product->category;
            return response()->json([
                'success' => true,
                'image' => asset($variant->image),
                'price' => $variant->price,
                'price_sale' => $variant->price_sale,
                'quantity' => $variant->quantity,
                'product_name' => $product->name,
                'category_name' => $category->Name ?? '',
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
