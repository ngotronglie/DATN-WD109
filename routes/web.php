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
Route::get('/products', [ClientController::class, 'products'])->name('products');
Route::get('/about', [ClientController::class, 'about'])->name('about');
Route::get('/contact', [ClientController::class, 'contact'])->name('contact');
Route::post('/contact', [ClientController::class, 'submitContact'])->name('contact.post');
Route::get('/blog', [ClientController::class, 'blog'])->name('blog');
Route::get('/search', [ClientController::class, 'search'])->name('search');
Route::get('/category/{slug}', [ClientController::class, 'category'])->name('category');
Route::get('/product/{slug}', [ClientController::class, 'productDetail'])->name('product.detail');
Route::get('/blog/{slug}', [ClientController::class, 'post'])->name('post');

Route::get('/account/order', [UserOrderController::class, 'index'])->name(name: 'account.order');
Route::get('/account/order/{id}', [UserOrderController::class, 'show'])->name('user.orders.show');
Route::post('/order/{id}/return', [UserOrderController::class, 'returnOrder'])->name('order.return');
Route::post('/refund', [UserOrderController::class, 'store'])->name('refund.store');

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
Route::get('/cart', function () {
    return view('layouts.user.cart');
})->name('cart');

Route::get('/wishlist', [App\Http\Controllers\FavoriteController::class, 'index'])->name('wishlist')->middleware('auth');

Route::get('/productdetail', function () {
    return view('layouts.user.productDetail');
})->name('productdetail');

Route::get('/cart', function () {
    return view('layouts.user.cart');
})->name('cart');

// REMOVE this block to avoid conflict and undefined variable error
// Route::get('/shop', function () {
//     return view('layouts.user.shop');
// })->name('shop');


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
    });

    // Users
    Route::resource('users', UserController::class);

    // Test lỗi
    Route::get('/test-404', fn() => abort(404));
    Route::get('/test-403', fn() => abort(403));

    // Contact
    Route::resource('contacts', ContactController::class);

    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{order}/detail', [OrderController::class, 'show'])->name('orders.detail');

    // Refund Requests
    Route::get('orders/{order}/refund/create', [OrderController::class, 'showRefundForm'])->name('refunds.create');
    Route::post('orders/{order}/refund', [OrderController::class, 'submitRefundForm'])->name('refunds.store');

    // Admin Refund Management
    Route::get('/refunds', [OrderController::class, 'refundRequests'])->name('refunds.list');
    Route::get('/refunds/{id}', [OrderController::class, 'showRefundDetail'])->name('refunds.detail');
    Route::post('/refunds/{id}/approve', [OrderController::class, 'approveRefund'])->name('refunds.approve');
    Route::post('/refunds/{id}/upload-proof', [OrderController::class, 'uploadRefundProof'])->name('refunds.uploadProof');
    Route::post('/admin/orders/{id}/confirm-receive', [RefundController::class, 'confirmReceiveBack'])
        ->name('orders.confirmReceiveBack');








    // Comments (admin)
    Route::resource('comments', CommentController::class)->only(['index', 'destroy']);

    // Favorites (admin)
    Route::resource('favorites', AdminFavoriteController::class);

    // Product Variants
    Route::resource('product_variants', ProductVariantController::class);
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
    Route::resource('tag_blogs', App\Http\Controllers\Admin\TagBlogController::class);
});

// Shop detail, VNPAY, Blog detail, Comments
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/vnpay/payment', [ClientController::class, 'vnpayPayment'])->name('vnpay.payment');
Route::get('/vnpay/return', [ClientController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/blogs', [\App\Http\Controllers\Client\BlogDetailController::class, 'index'])->name('client.blog.index');
Route::get('/blog-detail/{slug}', [\App\Http\Controllers\Client\BlogDetailController::class, 'show'])->name('blog.detail.show');
Route::post('/blogs/{blog}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
