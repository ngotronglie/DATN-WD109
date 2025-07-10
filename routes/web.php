<?php

use App\Http\Controllers\Auth\EmailOrderController;
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
Route::post('/contact', [App\Http\Controllers\Client\ClientController::class, 'submitContact'])->name('contact.post');
Route::get('/blog', [ClientController::class, 'blog'])->name('blog');
Route::get('/search', [ClientController::class, 'search'])->name('search');
Route::get('/category/{slug}', [ClientController::class, 'category'])->name('category');
Route::get('/product/{slug}', [ClientController::class, 'product'])->name('product');
Route::get('/blog/{slug}', [ClientController::class, 'post'])->name('post');
Route::get('/product/{slug}', [ClientController::class, 'productDetail'])->name('product.detail');

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

Route::get('/cart', function () {
    return view('index.clientdashboard');
})->name('cart');

Route::get('/wishlist', [App\Http\Controllers\FavoriteController::class, 'index'])->name('wishlist')->middleware('auth');

Route::get('/account', function () {
    return view('index.clientdashboard');
})->name('account');

Route::get('/productdetail', function () {
    return view('layouts.user.productDetail');
})->name('productdetail');

Route::get('/cart', function () {
    return view('layouts.user.cart');
})->name('cart');

Route::get( '/shop', function () {
    return view('layouts.user.shop');
})->name('shop');


// Route cũ (bị lỗi vì không truyền biến $blogs)
// Route::get( '/blog', function () {
//     return view('layouts.user.blog');
// })->name('blog');

// Route mới: dùng controller để truyền dữ liệu cho view
use App\Http\Controllers\Client\BlogDetailController;
Route::get('/blog', [BlogDetailController::class, 'index'])->name('blog');

Route::get( '/blogdetail', function () {
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

Route::get('/login', [LoginController::class, 'create'])->name( 'auth.login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

// Form đặt lại mật khẩu (từ email gửi về)
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

Route::get('/verify-email/{email}/{token}', [VerifyEmailController::class, 'verify'])->name('verify.email');

Route::post('/order/place', [EmailOrderController::class, 'placeOrder'])->name('order.place');
Route::post('/order/cancel/{id}', [EmailOrderController::class, 'cancelOrder'])->name('order.cancel');



Route::middleware('auth')->group(function () {
    // Các route yêu cầu người dùng đã đăng nhập
    Route::post('/favorites', [App\Http\Controllers\FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [App\Http\Controllers\FavoriteController::class, 'destroy']);
    Route::get('/favorites/check/{productId}', [App\Http\Controllers\FavoriteController::class, 'checkFavorite'])->name('favorites.check');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::post('/contact', [App\Http\Controllers\Client\ClientController::class, 'submitContact'])->name('contact.post');

//  route admin

// Route cho dashboard và tất cả các route admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin
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

    // Colors và Capacities
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

        // Quản lý ảnh biến thể
        Route::get('/{slug}/images', [ProductController::class, 'addfiledetail'])->name('addfiledetail');
        Route::put('/{slug}/images', [ProductController::class, 'updateImages'])->name('updateImages');
        Route::delete('/variants/{variantId}/images/{imageId}', [ProductController::class, 'deleteImage'])->name('deleteImage');
    });

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Test lỗi
    Route::get('/test-404', fn() => abort(404));
    Route::get('/test-403', fn() => abort(403));

    // Contact
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::post('/contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('contacts.updateStatus');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/{contact}/mark-replied', [ContactController::class, 'markAsReplied'])->name('contacts.markReplied');

    // Orders
    Route::resource('orders', OrderController::class);

    // cac route
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class)->only(['index', 'destroy']);
    Route::resource('favorites', AdminFavoriteController::class);
    Route::resource('vouchers', App\Http\Controllers\Admin\VoucherController::class);
    Route::resource('contacts', App\Http\Controllers\Admin\ContactController::class);
    Route::resource('colors', App\Http\Controllers\Admin\ColorController::class);
    Route::resource('capacities', App\Http\Controllers\Admin\CapacityController::class);
    Route::resource('product_variants', ProductVariantController::class);
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
    Route::resource('tag_blogs', App\Http\Controllers\Admin\TagBlogController::class);

});

// Route VNPAY
Route::get('/vnpay/payment', [ClientController::class, 'vnpayPayment'])->name('vnpay.payment');
Route::get('/vnpay/return', [ClientController::class, 'vnpayReturn'])->name('vnpay.return');
//route blogs
Route::get('/blogs', [\App\Http\Controllers\Client\BlogDetailController::class, 'index'])->name('client.blog.index');

Route::get('/blog-detail/{slug}', [\App\Http\Controllers\Client\BlogDetailController::class, 'show'])->name('blog.detail.show');

Route::post('/blogs/{blog}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
