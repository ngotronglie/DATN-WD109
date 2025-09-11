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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    /**
     * Merge session-based cart items into the authenticated user's database cart.
     */
    private function mergeSessionCartToDatabase(): void
    {
        if (!Auth::check()) {
            return;
        }

        $sessionCart = Session::get('cart', []);
        if (empty($sessionCart)) {
            return;
        }

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($sessionCart as $sessionItem) {
            $variantId = $sessionItem['product_variant_id'] ?? null;
            $quantity = (int) ($sessionItem['quantity'] ?? 0);
            if (!$variantId || $quantity < 1) {
                continue;
            }

            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variantId)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $quantity;
                $existingItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variantId,
                    'quantity' => $quantity,
                ]);
            }
        }

        // Clear session cart after merging
        Session::forget('cart');
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
                    'product_image' => $variant ? $variant->image : null,
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
        return view('layouts.user.main', [
            'categories' => $categories,
            'banners' => $banners,
            'discountedProducts' => $discountedProducts,
            'popularProducts' => $popularProducts,
        ]);
    }

    public function products()
    {
        $categories = Categories::with('children')->whereNull('parent_id')->get();
        $allCategories = Categories::where('Is_active', 1)->get();
        $products = Product::where('is_active', 1)->paginate(12);

        return view('layouts.user.shop', compact('categories', 'allCategories', 'products'));
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
        $quantity = (int) $request->input('quantity', 1);

        // Basic validation
        if ($quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Số lượng không hợp lệ']);
        }

        // Allow adding by product_id if variant_id is missing: pick the first available variant
        if (empty($variantId)) {
            $productId = $request->input('product_id');
            if (!empty($productId)) {
                $variant = ProductVariant::where('product_id', $productId)
                    ->where('quantity', '>', 0)
                    ->orderBy('id')
                    ->first();
                if ($variant) {
                    $variantId = $variant->id;
                } else {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm hiện không có biến thể khả dụng']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Thiếu thông tin biến thể sản phẩm']);
            }
        }

        // Validate stock availability for the variant
        $variantModel = ProductVariant::find($variantId);
        if (!$variantModel) {
            return response()->json(['success' => false, 'message' => 'Biến thể sản phẩm không tồn tại']);
        }

        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variantId)
                ->first();
            $currentQty = $item ? $item->quantity : 0;
            if ($currentQty + $quantity > $variantModel->quantity) {
                $maxAddable = max(0, $variantModel->quantity - $currentQty);
                return response()->json([
                    'success' => false,
                    'message' => $maxAddable > 0
                        ? 'Vượt quá số lượng tồn. Bạn chỉ có thể thêm tối đa ' . $maxAddable . ' sản phẩm.'
                        : 'Sản phẩm đã đạt số lượng tối đa trong giỏ theo tồn kho.',
                ]);
            }

            if ($item) {
                $item->quantity = $currentQty + $quantity;
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
            $currentQty = 0;
            foreach ($cart as &$item) {
                if ($item['product_variant_id'] == $variantId) {
                    $currentQty = (int) $item['quantity'];
                    if ($currentQty + $quantity > $variantModel->quantity) {
                        $maxAddable = max(0, $variantModel->quantity - $currentQty);
                        unset($item);
                        return response()->json([
                            'success' => false,
                            'message' => $maxAddable > 0
                                ? 'Vượt quá số lượng tồn. Bạn chỉ có thể thêm tối đa ' . $maxAddable . ' sản phẩm.'
                                : 'Sản phẩm đã đạt số lượng tối đa trong giỏ theo tồn kho.',
                        ]);
                    }
                    $item['quantity'] = $currentQty + $quantity;
                    $found = true;
                    break;
                }
            }
            unset($item);
            if (!$found) {
                if ($quantity > $variantModel->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vượt quá số lượng tồn. Tối đa có thể thêm ' . $variantModel->quantity . ' sản phẩm.',
                    ]);
                }
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
        // Ensure any guest cart is merged once the user is authenticated
        if (Auth::check()) {
            $this->mergeSessionCartToDatabase();
        }

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
            // Merge any existing session cart into DB on first API call after login
            $this->mergeSessionCartToDatabase();
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            $items = $cart
                ? $cart->items()->with('productVariant.product', 'productVariant.color', 'productVariant.capacity')->get()
                : collect();
            $result = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'name' => $item->productVariant->product->name ?? '',
                    'color' => $item->productVariant->color->name ?? '',
                    'capacity' => $item->productVariant->capacity->name ?? '',
                    'price' => $item->productVariant->price,
                    'image' => asset($item->productVariant->image),
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
                        'image' => asset($variant->image),
                    ];
                }
            }
        }
        return response()->json(['success' => true, 'data' => $result]);
    }

    public function getDistricts($provinceId)
    {
        if (!Schema::hasTable('devvn_quanhuyen')) {
            return response()->json([]);
        }
        $districts = DB::table('devvn_quanhuyen')
            ->where('matp', $provinceId)
            ->get(['maqh as id', 'name as ten_quan_huyen']);
        return response()->json($districts);
    }

    public function getWards($districtId)
    {
        if (!Schema::hasTable('devvn_xaphuongthitran')) {
            return response()->json([]);
        }
        $wards = DB::table('devvn_xaphuongthitran')
            ->where('maqh', $districtId)
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
        // Check stock against variant
        $variantModel = ProductVariant::find($variantId);
        if (!$variantModel) {
            return response()->json(['success' => false, 'message' => 'Biến thể không tồn tại']);
        }
        if ($quantity > $variantModel->quantity) {
            return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho hiện có']);
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
            $data = $request->all();
            $userId = auth()->check() ? auth()->id() : 0;
            $orderCode = strtoupper(bin2hex(random_bytes(6)));

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
            $order->status_method = 'chưa thanh toán';
            $order->save();

            // Lưu chi tiết đơn hàng
            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    \App\Models\OrderDetail::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $item['variant_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }
            }

            // ✅ Gửi mail nếu KHÔNG phải thanh toán qua VNPAY


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

            return response()->json(['success' => true, 'order_id' => $order->id, 'order_code' => $orderCode]);
        } catch (\Throwable $e) {
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
        $vnp_TmnCode = "6S2EDVG2"; // Mã website tại VNPAY
        $vnp_HashSecret = "858FPSI9DG2CJM41ZLCYFV3PGNZ07RCA"; // Chuỗi bí mật
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
            $order->status_method = '2';
            $order->status = true;
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
            return view('layouts.user.vnpay_fail', ['order' => $order]);
        }
    }
}
