<?php

use App\Http\Controllers\Auth\EmailOrderController;
use App\Http\Controllers\Client\UserOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\FavoriteController as AdminFavoriteController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Auth\AddressController;
use App\Http\Controllers\Client\ShopController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Client Routes
Route::get('/', [ClientController::class, 'index'])->name('home');

// Route để refresh CSRF token
Route::get('/csrf-token', function() {
    return response()->json(['token' => csrf_token()]);
});
Route::get('/products', [ClientController::class, 'products'])->name('products');
Route::get('/flash-sales', [ClientController::class, 'flashSales'])->name('flash-sales');
Route::get('/about', [ClientController::class, 'about'])->name('about');
Route::get('/contact', [ClientController::class, 'contact'])->name('contact');
Route::post('/contact', [ClientController::class, 'submitContact'])->name('contact.post');
Route::get('/blog', [ClientController::class, 'blog'])->name('blog');
Route::get('/search', [ClientController::class, 'search'])->name('search');
Route::get('/category/{slug}', [ClientController::class, 'category'])->name('category');
Route::get('/product/{slug}', [ClientController::class, 'productDetail'])->name('product.detail');
Route::get('/flash-sale-product/{slug}', [ClientController::class, 'flashSaleProductDetail'])->name('flash-sale.product.detail');
Route::post('/get-variant', [ClientController::class, 'getVariant'])->name('get.variant');
Route::get('/blog/{slug}', [ClientController::class, 'post'])->name('post');

Route::get('/account/order', [UserOrderController::class, 'index'])->name(name: 'account.order');
Route::get('/account/order/{id}', [UserOrderController::class, 'show'])->name('user.orders.show');
Route::post('/account/order/{id}/reorder', [UserOrderController::class, 'reorder'])->name('user.orders.reorder');
Route::post('/order/{id}/return', [UserOrderController::class, 'returnOrder'])->name('order.return');
// Khách xác nhận đã trả hàng (sau khi admin duyệt)
Route::post('/account/order/{id}/mark-returned', [UserOrderController::class, 'markReturned'])->name('user.orders.markReturned');
Route::post('/refund', [UserOrderController::class, 'store'])->name('refund.store');

Route::get('/account/{id}/fillinfo', [UserOrderController::class, 'fillinfo'])->name('account.fillinfo');
Route::put('/account/{id}/update-info', [UserOrderController::class, 'updateInfo'])->name('account.updateInfo');


Route::get('list', [AddressController::class, 'index'])->name('account.address_list');
Route::get('/create', [AddressController::class, 'create'])->name('account.address.create');
Route::post('/store', [AddressController::class, 'store'])->name('account.address.store');
Route::get('/{id}/edit', [AddressController::class, 'edit'])->name('account.address_edit');
Route::put('/{id}/update', [AddressController::class, 'update'])->name('account.address.update');
Route::delete('/{id}', [AddressController::class, 'delete'])->name('account.address.delete');
Route::post('/{id}/set-default', [AddressController::class, 'setDefault'])->name('account.address.setDefault');

Route::get('/address/districts/{provinceId}', [ClientController::class, 'getDistricts']);
Route::get('/address/wards/{districtId}', [ClientController::class, 'getWards']);
Route::get('/address/provinces', [ClientController::class, 'getProvinces']);

// Vouchers
Route::get('/vouchers/active', [ClientController::class, 'getActiveVouchers'])->name('vouchers.active');

// Old route for backward compatibility
Route::get('/address/wards-by-province/{province_id}', [AddressController::class, 'getWards']);

// Blog Detail Routes
Route::prefix('blog-detail')->name('blog.detail.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\BlogDetailController::class, 'index'])->name('index');
    Route::get('/{slug}', [\App\Http\Controllers\Client\BlogDetailController::class, 'show'])->name('show');
    Route::get('/tag/{tagId}', [\App\Http\Controllers\Client\BlogDetailController::class, 'searchByTag'])->name('tag');
    Route::get('/search', [\App\Http\Controllers\Client\BlogDetailController::class, 'search'])->name('search');

    // Admin routes (cần đăng nhập và là admin)

    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/create', [\App\Http\Controllers\Client\BlogDetailController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Client\BlogDetailController::class, 'store'])->name('store');
        Route::get('/{slug}/edit', [\App\Http\Controllers\Client\BlogDetailController::class, 'edit'])->name('edit');
        Route::put('/{slug}/update', [\App\Http\Controllers\Client\BlogDetailController::class, 'update'])->name('update');
        Route::delete('/{slug}/delete', [\App\Http\Controllers\Client\BlogDetailController::class, 'destroy'])->name('destroy');
    });
});

// Cart, Wishlist, Account, Shop, Checkout

Route::get('/cart', [ClientController::class, 'showCart'])->name('cart');


Route::get('/wishlist', [App\Http\Controllers\FavoriteController::class, 'index'])->name('wishlist')->middleware('auth');

// Route removed: product detail must be served via controller to provide required data

Route::get('/shop', [ClientController::class, 'products'])->name('shop.index');


// Route::get('/blog', function () {
//     return view('layouts.user.blog');
// })->name('blog');
// ✅ DÙNG CÁI NÀY
Route::get('/blogs', [\App\Http\Controllers\Client\BlogDetailController::class, 'index'])->name('client.blog.index');


Route::get('/blogdetail', function () {
    return view('layouts.user.blogdetail');
})->name('blogdetail');
Route::get('/checkout', function () {
    return view('layouts.user.checkout');
});

Route::get('/api/product-variant', [\App\Http\Controllers\Client\ClientController::class, 'getVariant']);
Route::get('/api/voucher', [\App\Http\Controllers\Client\ClientController::class, 'getVoucher']);
Route::post('/api/add-to-cart', [\App\Http\Controllers\Client\ClientController::class, 'apiAddToCart']);
Route::get('/api/cart', [\App\Http\Controllers\Client\ClientController::class, 'apiGetCart']);
Route::get('/api/user', [\App\Http\Controllers\Client\ClientController::class, 'apiGetUser']);
Route::post('/api/cart/update-qty', [\App\Http\Controllers\Client\ClientController::class, 'apiUpdateCartQty']);
Route::post('/api/cart/remove', [\App\Http\Controllers\Client\ClientController::class, 'apiRemoveCartItem']);
Route::post('/api/checkout', [\App\Http\Controllers\Client\ClientController::class, 'apiCheckout']);

// Product comments
Route::post('/product/{product}/comments', [\App\Http\Controllers\Client\ClientController::class, 'storeProductComment'])
    ->middleware('auth')
    ->name('product.comments.store');


Route::get('/register', [RegisterController::class, 'create'])->name('auth.register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'create'])->name('auth.login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
Route::get('/verify-email/{email}/{token}', [VerifyEmailController::class, 'verify'])->name('verify.email');

// Order routes
Route::post('/order/place', [EmailOrderController::class, 'placeOrder'])->name('order.place');
Route::post('/order/cancel/{id}', [EmailOrderController::class, 'cancelOrder'])->name('order.cancel');
// Cho phép người dùng tự hủy đơn ở trạng thái 0 hoặc 1
Route::post('/account/order/{id}/cancel', [UserOrderController::class, 'cancelOrder'])->name('user.orders.cancel');
// Người dùng xác nhận đã nhận hàng khi trạng thái đang vận chuyển (4)
Route::post('/account/order/{id}/confirm-received', [UserOrderController::class, 'confirmReceived'])->name('user.orders.confirmReceived');


Route::middleware(['auth'])->group(function () {
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::get('/account/password/change', [AccountController::class, 'changePassword'])->name('password.change');
    Route::put('/account/password/update', [AccountController::class, 'updatePassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);
    Route::get('/favorites/check/{productId}', [FavoriteController::class, 'checkFavorite'])->name('favorites.check');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'layouts.admin.index')->name('dashboard');

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Vouchers
    Route::resource('vouchers', VoucherController::class);

    // Banners
    Route::resource('banners', BannerController::class);
    Route::post('banners/{banner}/status', [BannerController::class, 'updateStatus'])->name('banners.updateStatus');

    // Blogs
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);

    // Tag Blogs
    Route::resource('tag-blogs', \App\Http\Controllers\Admin\TagBlogController::class)->except(['show']);

    // Flash Sales
    Route::prefix('flash-sales')->name('flash-sales.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FlashSalesController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\FlashSalesController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\FlashSalesController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Admin\FlashSalesController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\FlashSalesController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [\App\Http\Controllers\Admin\FlashSalesController::class, 'update'])->name('update');
        Route::match(['delete', 'post'], '/{id}/delete', [\App\Http\Controllers\Admin\FlashSalesController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\FlashSalesController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/statistics', [\App\Http\Controllers\Admin\FlashSalesController::class, 'statistics'])->name('statistics');
        Route::get('/api/products/by-category', [\App\Http\Controllers\Admin\FlashSalesController::class, 'getByCategory'])->name('products.byCategory');
        Route::get('/{id}/api/stats', [\App\Http\Controllers\Admin\FlashSalesController::class, 'apiStats'])->name('api.stats');
    });

    // Colors and Capacities
    Route::resource('colors', ColorController::class);
    Route::resource('capacities', CapacityController::class);

    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{slug}', [ProductController::class, 'edit'])->name('edit');
        Route::put('/update/{slug}', [ProductController::class, 'update'])->name('update');
        Route::delete('/delete/{slug}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/{slug}/images', [ProductController::class, 'addfiledetail'])->name('addfiledetail');
        Route::put('/{slug}/images', [ProductController::class, 'updateImages'])->name('updateImages');
        Route::delete('/variants/{variantId}/images/{imageId}', [ProductController::class, 'deleteImage'])->name('deleteImage');
        Route::get('/low-stock', [ProductController::class, 'lowStock'])->name('lowStock');
    });

    // Users
    Route::resource('users', UserController::class);

    // Test lỗi
    Route::get('/test-404', fn() => abort(404));
    Route::get('/test-403', fn() => abort(403));

    // Contact
    Route::resource('contacts', ContactController::class);
    Route::post('contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('contacts.updateStatus');
    Route::post('contacts/{contact}/mark-replied', [ContactController::class, 'markAsReplied'])->name('contacts.markReplied');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{order}/detail', [OrderController::class, 'show'])->name('orders.detail');

    Route::post('/admin/orders/{refund}/confirm-receive-back', [OrderController::class, 'confirmReceiveBack'])->name('orders.confirmReceiveBack');


    Route::post('orders/{refund}/confirm-receive-back', [OrderController::class, 'confirmReceiveBack'])->name('orders.confirmReceiveBack');

    // Delivery actions
    Route::post('/orders/{id}/delivery-success', [OrderController::class, 'deliverySuccess'])->name('orders.deliverySuccess');
    Route::post('/orders/{id}/delivery-failed', [OrderController::class, 'deliveryFailed'])->name('orders.deliveryFailed');
    Route::post('/orders/{id}/redeliver', [OrderController::class, 'redeliver'])->name('orders.redeliver');
    Route::post('/orders/{id}/cod-not-received', [OrderController::class, 'codNotReceived'])->name('orders.codNotReceived');


    // Refund Requests
    Route::get('orders/{order}/refund/create', [OrderController::class, 'showRefundForm'])->name('refunds.create');
    Route::post('orders/{order}/refund', [OrderController::class, 'submitRefundForm'])->name('refunds.store');

    // Admin Refund Management
    Route::get('/refunds', [OrderController::class, 'refundRequests'])->name('refunds.list');
    Route::get('/refunds/{id}', [OrderController::class, 'showRefundDetail'])->name('refunds.detail');
    Route::post('/refunds/{id}/approve', [OrderController::class, 'approveRefund'])->name('refunds.approve');
    // Duyệt yêu cầu hoàn hàng (không phải hoàn tiền)
    Route::post('/refunds/{id}/approve-return', [OrderController::class, 'approveReturn'])->name('refunds.approveReturn');
    Route::post('/refunds/{id}/upload-proof', [OrderController::class, 'uploadRefundProof'])->name('refunds.uploadProof');
    // Khởi tạo hoàn tiền khi đang đóng gói
    Route::post('/orders/{id}/refund/initiate', [OrderController::class, 'initiateRefund'])->name('orders.refund.initiate');


    // Xác thực hoàn hàng
    Route::post('orders/{id}/refund/verify', [OrderController::class, 'verify'])->name('refunds.verify');

    // Từ chối yêu cầu hoàn hàng
    Route::post('orders/{id}/refund/reject', [OrderController::class, 'reject'])->name('refunds.reject');








    // Comments (admin) → chuyển sang quản lý Bình luận SẢN PHẨM tại /admin/comments
    Route::get('comments', [\App\Http\Controllers\Admin\ProductCommentController::class, 'index'])->name('comments.index');
    Route::delete('comments/{comment}', [\App\Http\Controllers\Admin\ProductCommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('comments/{comment}/toggle', [\App\Http\Controllers\Admin\ProductCommentController::class, 'toggleVisibility'])->name('comments.toggle');

    // Giữ đường dẫn riêng nếu cần: /admin/product-comments (tùy chọn)
    Route::get('product-comments', [\App\Http\Controllers\Admin\ProductCommentController::class, 'index'])->name('product-comments.index');
    Route::delete('product-comments/{comment}', [\App\Http\Controllers\Admin\ProductCommentController::class, 'destroy'])->name('product-comments.destroy');
    Route::post('product-comments/{comment}/toggle', [\App\Http\Controllers\Admin\ProductCommentController::class, 'toggleVisibility'])->name('product-comments.toggle');

    // Favorites (admin)
    Route::resource('favorites', AdminFavoriteController::class);

    // Product Variants
    Route::resource('product_variants', ProductVariantController::class);
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
    Route::resource('tag_blogs', App\Http\Controllers\Admin\TagBlogController::class);

    // Thống kê
    Route::prefix('thongke')->name('thongke.')->group(function () {
        Route::get('/sanpham', [\App\Http\Controllers\Admin\StatisticController::class, 'sanpham'])->name('sanpham');
        Route::get('/donhang', [\App\Http\Controllers\Admin\StatisticController::class, 'donhang'])->name('donhang');
        Route::get('/donhang/revenue-orders', [\App\Http\Controllers\Admin\StatisticController::class, 'revenueOrders'])->name('donhang.revenue-orders');
        Route::get('/nguoidung', [\App\Http\Controllers\Admin\StatisticController::class, 'nguoidung'])->name('nguoidung');

        Route::get('/', function() { return view('layouts.admin.thongke.index'); })->name('index');

    });
});

// Shop detail, VNPAY, Blog detail, Comments
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/vnpay/payment', [ClientController::class, 'vnpayPayment'])->name('vnpay.payment');
Route::get('/vnpay/return', [ClientController::class, 'vnpayReturn'])->name('vnpay.return');
// Callback URL theo ENV VNP_RETURN_URL (ví dụ: http://127.0.0.1:8000/api/payment/result)
Route::get('/api/payment/result', [ClientController::class, 'vnpayReturn'])->name('vnpay.return.env');
Route::get('/blogs', [\App\Http\Controllers\Client\BlogDetailController::class, 'index'])->name('client.blog.index');
Route::get('/blog-detail/{slug}', [\App\Http\Controllers\Client\BlogDetailController::class, 'show'])->name('blog.detail.show');
Route::post('/blogs/{blog}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
