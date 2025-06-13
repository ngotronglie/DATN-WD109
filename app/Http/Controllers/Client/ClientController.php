<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', 1)->get();
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $featuredProducts = Product::where('is_active', 1)->take(8)->get();
        $newProducts = Product::where('is_active', 1)->latest()->take(8)->get();
        $bestSellers = Product::where('is_active', 1)->latest()->take(8)->get();

        return view('index.clientdashboard', compact(
            'banners',
            'categories',
            'featuredProducts',
            'newProducts',
            'bestSellers'
        ));
    }

    public function products()
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $products = Product::where('is_active', 1)->paginate(12);

        return view('client.products', compact('categories', 'products'));
    }

    public function category($slug)
    {
        $category = Categories::where('slug', $slug)->firstOrFail();
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $products = Product::where('is_active', 1)
            ->where('category_id', $category->id)
            ->paginate(12);

        return view('client.category', compact('category', 'categories', 'products'));
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $relatedProducts = Product::where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('client.product', compact('product', 'categories', 'relatedProducts'));
    }

    public function about()
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        return view('client.about', compact('categories'));
    }

    public function contact()
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        return view('client.contact', compact('categories'));
    }

    public function blog()
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $posts = Blog::where('is_active', 1)->paginate(6);
        return view('client.blog', compact('categories', 'posts'));
    }

    public function post($slug)
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $post = Blog::where('slug', $slug)->firstOrFail();
        $recentPosts = Blog::where('is_active', 1)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        return view('client.post', compact('categories', 'post', 'recentPosts'));
    }

    public function search(Request $request)
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $query = $request->input('q');
        $products = Product::where('is_active', 1)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12);

        return view('client.search', compact('categories', 'products', 'query'));
    }
}
