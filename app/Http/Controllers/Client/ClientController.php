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
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\CartItem;

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
        return view('layouts.user.contact');
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

    public function getVoucher(Request $request)
    {
        $code = $request->input('code');
        $voucher = Voucher::where('code', $code)
            ->where('is_active', 1)
            ->where('start_date', '<=', now())
            ->where('end_time', '>=', now())
            ->where('quantity', '>', 0)
            ->first();
        if ($voucher) {
            return response()->json([
                'success' => true,
                'discount' => $voucher->discount,
                'min_money' => $voucher->min_money,
                'max_money' => $voucher->max_money,
                'message' => 'Áp dụng thành công',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Mã không hợp lệ hoặc đã hết hạn',
            ]);
        }
    }

    public function apiAddToCart(Request $request)
    {
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);

        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variantId)
                ->first();
            if ($item) {
                $item->quantity += $quantity;
                $item->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity,
                ]);
            }
            return response()->json(['success' => true, 'type' => 'db', 'message' => 'Đã thêm vào giỏ hàng!']);
        } else {
            $cart = Session::get('cart', []);
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_variant_id'] == $variantId) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            unset($item);
            if (!$found) {
                $cart[] = [
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity,
                ];
            }
            Session::put('cart', $cart);
            return response()->json(['success' => true, 'type' => 'session', 'message' => 'Đã thêm vào giỏ hàng!']);
        }
    }

    public function showCart()
    {
        $user = Auth::user();
        if ($user) {
            $cart = Cart::where('user_id', $user->id)->first();
            $items = $cart ? $cart->items()->with('productVariant.product', 'productVariant.color', 'productVariant.capacity')->get() : collect();
        } else {
            $cart = Session::get('cart', []);
            $variantIds = array_column($cart, 'product_variant_id');
            $variants = ProductVariant::with('product', 'color', 'capacity')->whereIn('id', $variantIds)->get();
            $items = collect();
            foreach ($cart as $item) {
                $variant = $variants->where('id', $item['product_variant_id'])->first();
                if ($variant) {
                    $variant->cart_quantity = $item['quantity'];
                    $items->push($variant);
                }
            }
        }
        return view('layouts.user.cart', compact('items', 'user'));
    }

    public function apiGetCart(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            $items = $cart
                ? $cart->items()->with('productVariant.product', 'productVariant.color', 'productVariant.capacity')->get()
                : collect();
            $result = $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'name' => $item->productVariant->product->name ?? '',
                    'color' => $item->productVariant->color->name ?? '',
                    'capacity' => $item->productVariant->capacity->name ?? '',
                    'price' => $item->productVariant->price,
                    'image' => $item->productVariant->image,
                ];
            })->values();
        } else {
            $cart = Session::get('cart', []);
            $variantIds = array_column($cart, 'product_variant_id');
            $variants = ProductVariant::with('product', 'color', 'capacity')->whereIn('id', $variantIds)->get();
            $result = [];
            foreach ($cart as $item) {
                $variant = $variants->where('id', $item['product_variant_id'])->first();
                if ($variant) {
                    $result[] = [
                        'id' => null,
                        'variant_id' => $variant->id,
                        'quantity' => $item['quantity'],
                        'name' => $variant->product->name ?? '',
                        'color' => $variant->color->name ?? '',
                        'capacity' => $variant->capacity->name ?? '',
                        'price' => $variant->price,
                        'image' => $variant->image,
                    ];
                }
            }
        }
        return response()->json(['success' => true, 'data' => $result]);
    }

    public function apiGetUser(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                    'address' => $user->address ?? '',
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'user' => null
            ]);
        }
    }
}
