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
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductComment;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{


    /**
     * Helper function to get proper image URL
     */
    private function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        // Nếu ảnh đã có URL đầy đủ, sử dụng trực tiếp
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }

        // Nếu chỉ có đường dẫn tương đối, sử dụng asset()
        return asset($imagePath);
    }
    public function index()

    {
        // Get products split into discounted and popular (non-discounted)
        $baseQuery = Product::where('is_active', 1)
            ->with(['variants' => function($query) {
                $query->orderBy('id');
            }]);

        // Discounted products: any variant with price_sale > 0
        $discountedProducts = (clone $baseQuery)
            ->whereHas('variants', function($q) {
                $q->where('price_sale', '>', 0);
            })
            ->inRandomOrder()
            ->limit(8)
            ->get()
            ->map(function($product) {
                // Prefer in-stock variant with sale > 0, else any sale > 0, else fallback
                $variant = $product->variants->firstWhere('price_sale', '>', 0) ?? $product->variants->first();
                return (object) [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_view' => $product->view_count,
                    'product_slug' => $product->slug,
                    'product_image' => $this->getImageUrl($variant ? $variant->image : null),
                    'product_price' => $variant ? $variant->price : 0,
                    'product_price_discount' => $variant && $variant->price_sale > 0 ? $variant->price_sale : 0,
                ];
            });

        // Popular (non-discounted): products whose variants all have price_sale <= 0 or null
        $popularProducts = (clone $baseQuery)
            ->whereDoesntHave('variants', function($q) {
                $q->where('price_sale', '>', 0);
            })
            ->inRandomOrder()
            ->limit(8)
            ->get()
            ->map(function($product) {
                $variant = $product->variants->firstWhere('quantity', '>', 0) ?? $product->variants->first();
                return (object) [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_view' => $product->view_count,
                    'product_slug' => $product->slug,
                    'product_image' => $variant ? $variant->image : null,
                    'product_price' => $variant ? $variant->price : 0,
                    'product_price_discount' => 0,
                ];
            });
        $banners = \App\Models\Banner::where('is_active', 1)->orderByDesc('id')->get();
        $categories = \App\Models\Categories::whereNull('Parent_id')->where('Is_active', 1)->get();

        // Lấy danh sách sản phẩm mới nhất
        $products = \App\Models\Product::with(['variants', 'variants.color', 'variants.capacity'])
            ->where('Is_active', 1)
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        // Lấy flash sales đang hoạt động
        $flashSales = FlashSale::getActiveFlashSales();

        // Lấy bài viết mới nhất (giống như trang blog)
        $latestBlogs = Blog::with(['user'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('layouts.user.main', [
            'categories' => $categories,
            'banners' => $banners,
            'products' => $products,
            'flashSales' => $flashSales,
            'discountedProducts' => $discountedProducts ?? [],
            'popularProducts' => $popularProducts ?? [],
            'latestBlogs' => $latestBlogs
        ]);

    }


    public function products(Request $request)

    {
        $categories = Categories::with('children')->whereNull('Parent_id')->get();
        $allCategories = Categories::where('Is_active', 1)->get();

        $query = Product::with(['mainVariant', 'variants'])->where('is_active', 1);

        // Lọc theo danh mục
        $categoryId = $request->query('category');
        if (!empty($categoryId)) {
            $query->where('categories_id', (int) $categoryId);
        }

        // Lọc theo giá dựa trên biến thể
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        if ($minPrice !== null || $maxPrice !== null) {
            $query->whereHas('variants', function ($q) use ($minPrice, $maxPrice) {
                if ($minPrice !== null && $minPrice !== '') {
                    $q->where('price', '>=', (float) $minPrice);
                }
                if ($maxPrice !== null && $maxPrice !== '') {
                    $q->where('price', '<=', (float) $maxPrice);
                }
            });
        }

        // Sắp xếp
        $sort = $request->query('sort');
        if ($sort === 'az') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'za') {
            $query->orderBy('name', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('layouts.user.shop', compact('categories', 'allCategories', 'products'));
    }

    public function flashSales()
    {
        // Lấy tất cả flash sales đang hoạt động
        $flashSales = FlashSale::getActiveFlashSales();

        // Lấy categories để hiển thị filter
        $categories = Categories::whereNull('Parent_id')->where('Is_active', 1)->get();

        return view('layouts.user.flash-sales', compact('flashSales', 'categories'));
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
        // Redirect to the real product detail route using slug
        return redirect()->route('product.detail', ['slug' => $slug]);
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
        $blogs = \App\Models\Blog::with(['user', 'tags'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $recentBlogs = \App\Models\Blog::with('user')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $tags = \App\Models\TagBlog::withCount('blogs')->get();

        return view('layouts.user.blog', compact('blogs', 'recentBlogs', 'tags'));
    }

    public function post($slug)
    {
        $blog = \App\Models\Blog::with(['user', 'tags'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Tăng lượt xem
        $blog->increment('view');

        // Lấy blog liên quan
        $relatedBlogs = \App\Models\Blog::with('user')
            ->where('is_active', true)
            ->where('id', '!=', $blog->id)
            ->whereHas('tags', function ($query) use ($blog) {
                $query->whereIn('tag_blog.id', $blog->tags->pluck('id'));
            })
            ->orWhere('user_id', $blog->user_id)
            ->limit(3)
            ->get();

        // Lấy blog gần đây
        $recentBlogs = \App\Models\Blog::with('user')
            ->where('is_active', true)
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Lấy tất cả tags
        $tags = \App\Models\TagBlog::withCount('blogs')->get();

        return view('layouts.user.blogDetail', compact('blog', 'relatedBlogs', 'recentBlogs', 'tags'));
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

        // Lấy danh sách bình luận của sản phẩm
        $comments = \App\Models\ProductComment::with(['user', 'replies.user'])
            ->where('product_id', $product->id)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('layouts.user.productDetail', compact('product', 'variants', 'colors', 'capacities', 'categories', 'comments'));
    }

    public function flashSaleProductDetail($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        if (!request()->ajax() && request()->header('Purpose') !== 'prefetch') {
            $product->increment('view_count');
        }

        // Tìm flash sale đang hoạt động cho sản phẩm này
        $flashSale = FlashSale::where('is_active', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->whereHas('flashSaleProducts', function($query) use ($product) {
                $query->whereHas('productVariant', function($q) use ($product) {
                    $q->where('product_id', $product->id);
                });
            })
            ->first();

        if (!$flashSale) {
            // Nếu không có flash sale, chuyển về trang chi tiết thường
            return redirect()->route('product.detail', $slug);
        }

        // Lấy thông tin flash sale product
        $flashSaleProduct = $flashSale->flashSaleProducts()
            ->whereHas('productVariant', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->first();

        if (!$flashSaleProduct) {
            return redirect()->route('product.detail', $slug);
        }

        $variants = $product->variants()->where('quantity', '>', 0)->get();
        $colors = Color::all();
        $capacities = Capacity::all();
        $categories = \App\Models\Categories::with('children')->whereNull('Parent_id')->get();

        // Lấy danh sách bình luận của sản phẩm trong flash sale
        $comments = \App\Models\ProductComment::with(['user', 'replies.user'])
            ->where('product_id', $product->id)
            ->where('flash_sale_id', $flashSale->id)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('layouts.user.flash-sale-product-detail', compact(
            'product',
            'variants',
            'colors',
            'capacities',
            'categories',
            'flashSale',
            'flashSaleProduct',
            'comments'
        ));
    }

    public function storeProductComment(Request $request, $productId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer',
            'flash_sale_id' => 'nullable|exists:flash_sales,id'
        ]);

        $product = Product::findOrFail($productId);

        $commentData = [
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'parent_id' => $request->input('parent_id')
        ];

        // Nếu có flash_sale_id và sản phẩm đang trong flash sale
        if ($request->has('flash_sale_id')) {
            $flashSale = \App\Models\FlashSale::find($request->input('flash_sale_id'));
            if ($flashSale && $flashSale->isActive()) {
                $commentData['flash_sale_id'] = $flashSale->id;
            }
        }

        ProductComment::create($commentData);

        return back()->with('success', 'Đã gửi bình luận');
    }

    public function getVariant(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'color_id' => 'required|exists:colors,id',
                'capacity_id' => 'required|exists:capacities,id'
            ]);

            // Lấy thông tin sản phẩm
            $product = Product::findOrFail($validated['product_id']);

            // Tìm biến thể phù hợp
            $variant = ProductVariant::with(['color', 'capacity', 'product.category'])
                ->where('product_id', $validated['product_id'])
                ->where('color_id', $validated['color_id'])
                ->where('capacity_id', $validated['capacity_id'])
                ->first();

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Biến thể không tồn tại',
                    'code' => 404
                ], 404);
            }

            // Kiểm tra flash sale
            $flashSaleProduct = \App\Models\FlashSaleProduct::with('flashSale')
                ->where('product_variant_id', $variant->id)
                ->whereHas('flashSale', function($query) {
                    $query->where('start_time', '<=', now())
                          ->where('end_time', '>=', now())
                          ->where('status', 'active');
                })
                ->first();

            // Xây dựng dữ liệu trả về
            $response = [
                'success' => true,
                'id' => $variant->id,
                'product_id' => $variant->product_id,
                'product_name' => $product->name,
                'category_name' => $variant->product->category->Name ?? '',
                'color_id' => $variant->color_id,
                'capacity_id' => $variant->capacity_id,
                'price' => (float) $variant->price,
                'price_sale' => $variant->price_sale ? (float) $variant->price_sale : null,
                'stock' => (int) $variant->quantity,
                'image' => $variant->image
                    ? (str_starts_with($variant->image, 'http')
                        ? $variant->image
                        : asset('storage/' . $variant->image))
                    : asset('images/no-image.png'),
                'is_flash_sale' => (bool) $flashSaleProduct,
            ];

            // Nếu có trong flash sale
            if ($flashSaleProduct) {
                $response['flash_sale_price'] = (float) $flashSaleProduct->sale_price;
                $response['original_price'] = (float) $variant->price;
                $response['is_flash_sale'] = true;
            } else {
                $response['is_flash_sale'] = false;
            }

            return response()->json($response);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors(),
                'code' => 422
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy thông tin biến thể: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
                'code' => 500
            ], 500);
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

    public function getActiveVouchers()
    {
        $vouchers = Voucher::query()
            ->where('is_active', 1)
            ->where('start_date', '<=', now())
            ->where('end_time', '>=', now())
            ->where('quantity', '>', 0)
            ->orderByDesc('created_at')
            ->get(['id', 'code', 'discount', 'min_money', 'max_money']);

        return response()->json($vouchers);
    }

    public function apiAddToCart(Request $request)
    {
        $variantId = $request->input('product_variant_id') ?? $request->input('variant_id');
        $colorId = $request->input('color_id');
        $capacityId = $request->input('capacity_id');
        $isFlashSale = $request->boolean('is_flash_sale', false);
        $flashSaleId = $request->input('flash_sale_id');
        $flashSalePrice = $request->input('flash_sale_price');
        $quantity = $request->input('quantity', 1);

        // Nếu không có variantId nhưng có color và capacity, tìm variant tương ứng
        if (!$variantId && $colorId && $capacityId) {
            $variant = ProductVariant::where('product_id', $request->input('product_id'))
                ->where('color_id', $colorId)
                ->where('capacity_id', $capacityId)
                ->first();

            if (!$variant) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã hết hàng']);
            }
            $variantId = $variant->id;
        } else {
            $variant = ProductVariant::find($variantId);
        }

        if (!$variant) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        }

        if ($quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Số lượng không hợp lệ']);
        }

        // Kiểm tra số lượng tồn kho cho flash sale
        if ($isFlashSale && $flashSaleId) {
            $flashSaleProduct = \App\Models\FlashSaleProduct::where('flash_sale_id', $flashSaleId)
                ->where('product_variant_id', $variantId)
                ->first();

            if (!$flashSaleProduct || $flashSaleProduct->remaining_stock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm flash sale đã hết hàng hoặc vượt quá số lượng cho phép'
                ]);
            }
        } else {
            // Kiểm tra tồn kho thông thường
            if ($quantity > $variant->quantity) {
                return response()->json(['success' => false, 'message' => 'Vượt quá số lượng tồn kho']);
            }
        }

        $cartData = [
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
        ];

        // Set the correct price based on whether it's a flash sale or regular product
        if ($isFlashSale && $flashSaleId) {
            $cartData['is_flash_sale'] = true;
            $cartData['flash_sale_id'] = $flashSaleId;
            $cartData['price'] = $flashSalePrice; // Flash sale price
        } else {
            // For regular products, use sale price if available, otherwise use regular price
            $cartData['price'] = $variant->price_sale ?? $variant->price;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Tìm sản phẩm trong giỏ hàng
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variantId)
                ->when($isFlashSale, function($query) use ($flashSaleId) {
                    return $query->where('flash_sale_id', $flashSaleId);
                }, function($query) {
                    return $query->whereNull('flash_sale_id');
                })
                ->first();

            if ($item) {
                $newQty = $item->quantity + $quantity;
                if ($isFlashSale && $flashSaleProduct && $newQty > $flashSaleProduct->remaining_stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vượt quá số lượng tồn kho cho flash sale'
                    ]);
                } elseif (!$isFlashSale && $newQty > $variant->quantity) {
                    return response()->json(['success' => false, 'message' => 'Vượt quá số lượng tồn kho']);
                }

                $item->quantity = $newQty;
                if ($isFlashSale) {
                    $item->price = $flashSalePrice;
                    $item->flash_sale_id = $flashSaleId;
                }
                $item->save();
            } else {
                $itemData = [
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity,
                    'price' => $isFlashSale ? $flashSalePrice : ($variant->price_sale ?? $variant->price)
                ];

                if ($isFlashSale) {
                    $itemData['flash_sale_id'] = $flashSaleId;
                }

                CartItem::create($itemData);
            }

            $cartCount = $cart->items()->sum('quantity');
            return response()->json([
                'success' => true,
                'type' => 'db',
                'message' => 'Đã thêm vào giỏ hàng!',
                'cart_count' => $cartCount
            ]);

        } else {
            $cart = Session::get('cart', []);
            $found = false;

            foreach ($cart as &$item) {
                $isSameVariant = $item['product_variant_id'] == $variantId;
                $isSameFlashSale = ($item['flash_sale_id'] ?? null) == $flashSaleId;

                if ($isSameVariant && ($isFlashSale ? $isSameFlashSale : !isset($item['flash_sale_id']))) {
                    $newQty = $item['quantity'] + $quantity;

                    if ($isFlashSale && $flashSaleProduct && $newQty > $flashSaleProduct->remaining_stock) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Vượt quá số lượng tồn kho cho flash sale'
                        ]);
                    } elseif (!$isFlashSale && $newQty > $variant->quantity) {
                        return response()->json(['success' => false, 'message' => 'Vượt quá số lượng tồn kho']);
                    }

                    $item['quantity'] = $newQty;
                    if ($isFlashSale) {
                        $item['price'] = $flashSalePrice;
                        $item['flash_sale_id'] = $flashSaleId;
                    }
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $newItem = [
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity,
                    'price' => $isFlashSale ? $flashSalePrice : ($variant->price_sale ?? $variant->price)
                ];

                if ($isFlashSale) {
                    $newItem['flash_sale_id'] = $flashSaleId;
                }

                $cart[] = $newItem;
            }

            Session::put('cart', $cart);

            // Tính tổng số lượng sản phẩm trong giỏ hàng
            $cartCount = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'type' => 'session',
                'message' => 'Đã thêm vào giỏ hàng!',
                'cart_count' => $cartCount
            ]);
        }
    }


    public function showCart()
    {
        $user = Auth::user();
        if ($user) {
            $cart = Cart::where('user_id', $user->id)->first();
            $items = $cart
                ? $cart->items()->with('productVariant.product', 'productVariant.color', 'productVariant.capacity')->get()
                : collect();
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

        // 👉 Lấy danh sách tỉnh/thành để truyền qua view (an toàn nếu bảng chưa tồn tại)
        $provinces = collect();
        try {
            if (Schema::hasTable('tinhthanh')) {
                $provinces = DB::table('tinhthanh')->get(['id', 'ten_tinh']);
            }
        } catch (\Throwable $e) {
            \Log::warning('Không thể tải danh sách tỉnh/thành: ' . $e->getMessage());
        }

        return view('layouts.user.cart', compact('items', 'user', 'provinces'));
    }


    public function apiGetCart(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            $items = $cart
                ? $cart->items()->with('productVariant.product', 'productVariant.color', 'productVariant.capacity')->get()
                : collect();
            $result = $items->map(function ($item) {
                $variant = $item->productVariant;
                if (!$variant) {
                    return null;
                }

                $isFlashSale = !is_null($item->flash_sale_id);

                // Always use the price stored in the cart item if available
                $effectivePrice = $item->price;

                // If no price is set in cart item, fall back to variant's sale price or regular price
                if (is_null($effectivePrice)) {
                    $effectivePrice = $variant->price_sale ?? $variant->price;
                }

                // For flash sale items, ensure we have the correct original price
                $originalPrice = $variant->price;
                if ($isFlashSale) {
                    $fsp = \App\Models\FlashSaleProduct::find($item->flash_sale_id);
                    if ($fsp) {
                        $effectivePrice = (float) $fsp->sale_price;
                        $originalPrice = (float) ($fsp->original_price ?? $variant->price);
                    }
                }

                return [
                    'id' => $item->id,
                    'variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'name' => $variant->product->name ?? 'Sản phẩm không xác định',
                    'color' => $variant->color->name ?? 'Không xác định',
                    'capacity' => $variant->capacity->name ?? 'Không xác định',
                    'price' => (float) $effectivePrice,
                    'original_price' => (float) $originalPrice,
                    'is_flash_sale' => $isFlashSale,
                    'image' => $this->getImageUrl($variant->image),
                ];
            })->filter()->values(); // Remove any null items
        } else {
            $cart = Session::get('cart', []);
            $variantIds = array_column($cart, 'product_variant_id');
            $variants = ProductVariant::with('product', 'color', 'capacity')->whereIn('id', $variantIds)->get();
            $result = [];

            foreach ($cart as $item) {
                $variant = $variants->where('id', $item['product_variant_id'])->first();
                if (!$variant) continue;

                $isFlashSale = !empty($item['flash_sale_id']);

                // Use the price from the cart item if available, otherwise use variant's price
                $effectivePrice = $item['price'] ?? ($variant->price_sale ?? $variant->price);
                $originalPrice = $variant->price;

                if ($isFlashSale && !empty($item['flash_sale_id'])) {
                    $fsp = \App\Models\FlashSaleProduct::find($item['flash_sale_id']);
                    if ($fsp) {
                        $effectivePrice = (float) $fsp->sale_price;
                        $originalPrice = (float) ($fsp->original_price ?? $variant->price);
                    }
                }

                $result[] = [
                    'id' => null,
                    'variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'name' => $variant->product->name ?? 'Sản phẩm không xác định',
                    'color' => $variant->color->name ?? 'Không xác định',
                    'capacity' => $variant->capacity->name ?? 'Không xác định',
                    'price' => (float) $effectivePrice,
                    'original_price' => (float) $originalPrice,
                    'is_flash_sale' => $isFlashSale,
                    'image' => $this->getImageUrl($variant->image)
                ];
            }
        }

        return response()->json(['success' => true, 'data' => $result]);
        return response()->json($provinces);
    }
    public function getDistricts($provinceId)
    {
        if (!Schema::hasTable('devvn_quanhuyen') || !Schema::hasTable('tinhthanh')) {
            return response()->json([]);
        }

        // Lấy mã tỉnh từ ID
        $province = DB::table('tinhthanh')->where('id', $provinceId)->first();
        if (!$province) {
            return response()->json([]);
        }

        $districts = DB::table('devvn_quanhuyen')
            ->where('matp', $province->ma_tinh)
            ->get(['id', 'name as ten_quan_huyen']);
        return response()->json($districts);
    }

    public function getWards($districtId)
    {
        if (!Schema::hasTable('devvn_xaphuongthitran')) {
            return response()->json([]);
        }

        // Lấy maqh từ bảng devvn_quanhuyen dựa trên districtId
        $district = DB::table('devvn_quanhuyen')
            ->where('id', $districtId)
              ->orWhere('maqh', $districtId)
            ->first();

        if (!$district) {
            return response()->json([]);
        }

        $wards = DB::table('devvn_xaphuongthitran')
            ->where('maqh', $district->maqh)
            ->get(['xaid as id', 'name as ten_phuong_xa']);
        return response()->json($wards);
    }


    public function apiGetUser(Request $request)
    {
        $user = auth()->user();
        $defaultAddress = $user->addresses()->where('is_default', 1)->first();

        $street = $defaultAddress->street ?? '';
        $ward = $defaultAddress->ward ?? '';
        $district = $defaultAddress->district ?? '';
        $city = $defaultAddress->city ?? '';
        $fullAddress = trim("{$street}, {$ward}, {$district}, {$city}", ', ');

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $defaultAddress->phone ?? '',
                'receiver_name' => $defaultAddress->receiver_name ?? $user->name,
                'street' => $street,
                'ward' => $ward,
                'district' => $district,
                'city' => $city,
                'address' => $fullAddress,
            ]
        ]);
    }



    public function submitContact(ContactRequest $request)
    {
        try {
            // Lấy dữ liệu đã được validate
            $validatedData = $request->validated();

            // Thêm user_id nếu user đã đăng nhập (ưu tiên từ request, nếu không có thì lấy từ auth)
            if (auth()->check()) {
                $validatedData['user_id'] = $request->input('user_id') ?? auth()->id();
            }

            // Debug: Log dữ liệu
            \Log::info('Contact form data:', $validatedData);
            \Log::info('User authenticated:', ['user_id' => auth()->id() ?? 'not logged in']);

            // Lưu vào database
            $contact = Contact::create($validatedData);

            \Log::info('Contact created successfully:', ['id' => $contact->id, 'user_id' => $contact->user_id]);

            return response()->json([
                'success' => true,
                'message' => 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Contact form error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function apiUpdateCartQty(Request $request)
    {
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity');
        if (!$variantId || !$quantity || $quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }

        $variant = ProductVariant::find($variantId);
        if (!$variant) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        }
        if ($quantity > $variant->quantity) {
            return response()->json(['success' => false, 'message' => 'Vượt quá số lượng tồn kho']);
        }
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart)
                return response()->json(['success' => false, 'message' => 'Không tìm thấy giỏ hàng']);
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variantId)
                ->first();
            if ($item) {
                $item->quantity = $quantity;
                $item->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
            }
        } else {
            $cart = Session::get('cart', []);
            foreach ($cart as &$item) {
                if ($item['product_variant_id'] == $variantId) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            unset($item);
            Session::put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function apiRemoveCartItem(Request $request)
    {
        $variantId = $request->input('variant_id');
        if (!$variantId) {
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        }
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart)
                return response()->json(['success' => false, 'message' => 'Không tìm thấy giỏ hàng']);
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variantId)
                ->first();
            if ($item) {
                $item->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
            }
        } else {
            $cart = Session::get('cart', []);
            $cart = array_filter($cart, function ($item) use ($variantId) {
                return $item['product_variant_id'] != $variantId;
            });
            Session::put('cart', array_values($cart));
            return response()->json(['success' => true]);
        }
    }

    public function apiCheckout(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $userId = auth()->check() ? auth()->id() : 0;
            $orderCode = strtoupper(bin2hex(random_bytes(6)));

            // Validate stock for each item before creating order
            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $variant = ProductVariant::find($item['variant_id'] ?? 0);
                    if (!$variant) {
                        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
                    }
                    if (($item['quantity'] ?? 0) < 1) {
                        return response()->json(['success' => false, 'message' => 'Số lượng không hợp lệ']);
                    }
                    if ($item['quantity'] > $variant->quantity) {
                        return response()->json(['success' => false, 'message' => 'Vượt quá số lượng tồn kho']);
                    }
                }
            }

            // Xử lý voucher theo code nếu có
            $voucherId = $data['voucher_id'] ?? null;
            if (empty($voucherId) && !empty($data['voucher_code'])) {
                $voucher = \App\Models\Voucher::where('code', $data['voucher_code'])->first();
                if ($voucher) {
                    $voucherId = $voucher->id;
                    if ($voucher->quantity > 0) {
                        $voucher->decrement('quantity', 1);
                    }
                }
            } elseif (!empty($voucherId)) {
                $voucher = \App\Models\Voucher::find($voucherId);
                if ($voucher && $voucher->quantity > 0) {
                    $voucher->decrement('quantity', 1);
                }
            }

            $order = new \App\Models\Order();
            $order->user_id = $userId;
            $order->price = $data['price'] ?? 0;
            $order->name = $data['name'] ?? '';
            $order->address = $data['address'] ?? '';
            $order->email = $data['email'] ?? '';
            $order->phone = $data['phone'] ?? '';
            $order->note = $data['note'] ?? '';
            $order->total_amount = $data['total_amount'] ?? 0;
            $order->status = 0; // chờ xử lý
            $order->payment_method = $data['payment_method'] ?? 'COD';
            $order->order_code = $orderCode;
            $order->voucher_id = $voucherId;
            // 0 = chưa thanh toán, 1 = đã thanh toán (COD khi giao), 2 = đã thanh toán (chuyển khoản)
            $order->status_method = 0;
            $order->save();

            // Lưu chi tiết đơn hàng và trừ tồn kho
            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if (!$variant) {
                        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
                    }
                    if ($item['quantity'] > $variant->quantity) {
                        return response()->json(['success' => false, 'message' => 'Vượt quá số lượng tồn kho']);
                    }

                    // Xác định flash sale sản phẩm (nếu có) và trừ tồn kho flash sale
                    $flashSaleIdForLine = null;
                    $fsp = null;
                    $isItemFlashSale = !empty($item['is_flash_sale']) || isset($item['flash_sale_id']);
                    if ($isItemFlashSale && !empty($item['flash_sale_id'])) {
                        $fsp = \App\Models\FlashSaleProduct::where('flash_sale_id', $item['flash_sale_id'])
                            ->where('product_variant_id', $item['variant_id'])
                            ->whereHas('flashSale', function ($q) { $q->ongoing(); })
                            ->first();
                    } else {
                        $fsp = \App\Models\FlashSaleProduct::where('product_variant_id', $item['variant_id'])
                            ->whereHas('flashSale', function ($q) { $q->ongoing(); })
                            ->first();
                    }

                    if ($fsp) {
                        if ($fsp->remaining_stock < (int) $item['quantity']) {
                            return response()->json(['success' => false, 'message' => 'Vượt quá số lượng Flash Sale còn lại']);
                        }
                        // Trừ tồn kho flash sale
                        $fsp->decrement('remaining_stock', (int) $item['quantity']);
                        $flashSaleIdForLine = $fsp->flash_sale_id;
                    }

                    // Tạo chi tiết đơn hàng, đính kèm flash_sale_id nếu có
                    \App\Models\OrderDetail::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $item['variant_id'],
                        'flash_sale_id' => $flashSaleIdForLine,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);

                    // Trừ tồn kho biến thể thực tế
                    $variant->decrement('quantity', (int) $item['quantity']);
                }
            }

            // ✅ Gửi mail nếu KHÔNG phải thanh toán qua VNPAY
            if (strtolower($order->payment_method) !== 'vnpay' && !empty($order->email)) {
                try {
                    Mail::send('emails.order-success', compact('order'), function ($message) use ($order) {
                        $message->to($order->email);
                        $message->subject('Xác nhận đơn hàng #' . $order->order_code);
                    });
                } catch (\Exception $e) {
                    \Log::error('Lỗi gửi mail đơn hàng #' . $order->order_code . ': ' . $e->getMessage());
                }
            } elseif (empty($order->email)) {
                \Log::warning('Không gửi được mail vì email trống cho đơn hàng #' . $order->order_code);
            }

            // Xóa cart
            if ($userId) {
                $cart = \App\Models\Cart::where('user_id', $userId)->first();
                if ($cart) {
                    $cart->items()->delete();
                    $cart->delete();
                }
            } else {
                \Session::forget('cart');
            }

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id, 'order_code' => $orderCode]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Khởi tạo thanh toán VNPAY sandbox
     */
    public function vnpayPayment(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = \App\Models\Order::find($orderId);
        if (!$order) {
            return redirect('/checkout')->with('error', 'Không tìm thấy đơn hàng');
        }
        // Cấu hình VNPAY sandbox
        $vnp_TmnCode = "HRDYTL3E"; // Mã website tại VNPAY
        $vnp_HashSecret = "MXSQ5VQKM5S176MJD4LHHU0B03Q9MCA8"; // Chuỗi bí mật
        $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TxnRef = $order->order_code;
        $vnp_OrderInfo = 'Thanh toan don hang ' . $order->order_code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total_amount * 100; // VNPAY yêu cầu nhân 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')),
        );

        \Log::info('VNPay Redirect Debug', [
            'inputData' => $inputData,
        ]);

        ksort($inputData);
        $query = "";
        $hashdata = '';
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    /**
     * Nhận callback từ VNPAY
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $order = \App\Models\Order::where('order_code', $vnp_TxnRef)->first();

        if ($order && $vnp_ResponseCode == '00') {
            // Đánh dấu đã thanh toán online (2 = chuyển khoản)
            $order->status_method = 2;
            $order->payment_method = 'vnpay';
            $order->save();

            // Gửi mail xác nhận
            if (!empty($order->email)) {
                try {
                    Mail::send('emails.order-success', compact('order'), function ($message) use ($order) {
                        $message->to($order->email);
                        $message->subject('Xác nhận đơn hàng #' . $order->order_code);
                    });
                } catch (\Exception $e) {
                    \Log::error('Lỗi gửi mail đơn hàng #' . $order->order_code . ': ' . $e->getMessage());
                }
            } else {
                \Log::warning('Không gửi được mail vì email trống cho đơn hàng #' . $order->order_code);
            }

            return view('layouts.user.vnpay_success', ['order' => $order]);
        } else {
            // Đánh dấu đơn hàng thất bại và khôi phục tồn kho, voucher
            if ($order) {
                // Cập nhật trạng thái đơn và phương thức thanh toán
                $order->status = 6; // Đã hủy
                $order->payment_method = 'vnpay';
                $order->status_method = 0; // chưa thanh toán
                $order->save();

                // Khôi phục tồn kho các sản phẩm trong đơn
                foreach ($order->orderDetails as $detail) {
                    if ($detail->productVariant) {
                        $detail->productVariant->increment('quantity', (int) $detail->quantity);
                    }
                }

                // Khôi phục số lượng voucher nếu có
                if (!empty($order->voucher_id)) {
                    $voucher = \App\Models\Voucher::find($order->voucher_id);
                    if ($voucher) {
                        $voucher->increment('quantity', 1);
                    }
                }
            }

            return view('layouts.user.vnpay_fail', ['order' => $order]);
        }
    }

    public function apiGetProductOptions(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $slug = $request->input('slug');

            $query = Product::query()->with(['variants' => function($q) {
                $q->with(['color:id,name', 'capacity:id,name'])
                  ->orderBy('id', 'asc');
            }]);

            if ($productId) {
                $query->where('id', $productId);
            } elseif ($slug) {
                $query->where('slug', $slug);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu product_id hoặc slug'
                ], 422);
            }

            $product = $query->first();
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            $colors = [];
            $capacities = [];
            $combinations = [];

            foreach ($product->variants as $v) {
                if ($v->quantity <= 0) {
                    // Bỏ biến thể hết hàng khỏi danh sách kết hợp
                    continue;
                }
                if ($v->color) {
                    $colors[$v->color->id] = [
                        'id' => $v->color->id,
                        'name' => $v->color->name,
                    ];
                }
                if ($v->capacity) {
                    $capacities[$v->capacity->id] = [
                        'id' => $v->capacity->id,
                        'name' => $v->capacity->name,
                    ];
                }

                // Kiểm tra flash sale cho biến thể này
                $fsp = \App\Models\FlashSaleProduct::with('flashSale')
                    ->where('product_variant_id', $v->id)
                    ->whereHas('flashSale', function($q) {
                        $q->where('start_time', '<=', now())
                          ->where('end_time', '>=', now())
                          ->where('status', 'active');
                    })
                    ->first();

                $isFlash = (bool) $fsp;
                $effectivePrice = $isFlash ? (float) $fsp->sale_price : ((float) ($v->price_sale ?? $v->price));
                $originalPrice = (float) $v->price;

                $combinations[] = [
                    'variant_id' => $v->id,
                    'color_id' => $v->color_id,
                    'capacity_id' => $v->capacity_id,
                    'price' => (float) $v->price,
                    'price_sale' => $v->price_sale ? (float) $v->price_sale : null,
                    'stock' => (int) $v->quantity,
                    'image' => $this->getImageUrl($v->image),
                    'is_flash_sale' => $isFlash,
                    'flash_sale_id' => $isFlash ? $fsp->flash_sale_id : null,
                    'flash_sale_price' => $isFlash ? (float) $fsp->sale_price : null,
                    'effective_price' => $effectivePrice,
                    'original_price' => $originalPrice,
                ];
            }

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $this->getImageUrl(optional($product->variants->first())->image)
                ],
                'colors' => array_values($colors),
                'capacities' => array_values($capacities),
                'combinations' => $combinations,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in apiGetProductOptions: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
