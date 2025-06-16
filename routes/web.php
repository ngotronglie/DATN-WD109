<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Client\ClientController;
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
Route::get('/blog', [ClientController::class, 'blog'])->name('blog');
Route::get('/search', [ClientController::class, 'search'])->name('search');
Route::get('/category/{slug}', [ClientController::class, 'category'])->name('category');
Route::get('/product/{slug}', [ClientController::class, 'product'])->name('product');
Route::get('/blog/{slug}', [ClientController::class, 'post'])->name('post');

Route::get('/cart', function () {
    return view('index.clientdashboard');
})->name('cart');

Route::get('/wishlist', function () {
    return view('index.clientdashboard');
})->name('wishlist');

Route::get('/account', function () {
    return view('index.clientdashboard');
})->name('account');

Route::get('/login', function () {
    return view('index.clientdashboard');
})->name('login');

//  route admin

// Trang dashboard admin
Route::view('/admin', 'layouts.admin.index')->name('admin.dashboard');

// Nhóm routes cho quản lý categories
Route::prefix('admin/categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
});

// Routes cho quản lý Voucher

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('vouchers', VoucherController::class);
    Route::resource('banners', BannerController::class);
    Route::post('banners/{banner}/status', [BannerController::class, 'updateStatus'])->name('banners.updateStatus');
});
// Nhóm routes cho quản lý màu sắc
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('colors', ColorController::class);
});
// end route admin


// Nhóm routes cho quản lý màu sắc
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('colors', ColorController::class);
    Route::resource('capacities', CapacityController::class);
});

// Nhóm routes cho quản lý roles
Route::prefix('admin/roles')->name('admin.roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');          // danh sách role
    Route::get('/create', [RoleController::class, 'create'])->name('create');  // form tạo mới
    Route::post('/', [RoleController::class, 'store'])->name('store');         // lưu role mới
    Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');   // form chỉnh sửa role
    Route::put('/{id}', [RoleController::class, 'update'])->name('update');    // cập nhật role
    Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');// xóa role
});
// end route admin

// Nhóm routes cho quản lý sản phẩm
Route::prefix('admin/products')->name('admin.products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('destroy');

    // Routes cho quản lý ảnh biến thể
    Route::get('/{id}/images', [ProductController::class, 'addfiledetail'])->name('addfiledetail');
    Route::put('/{id}/images', [ProductController::class, 'updateImages'])->name('updateImages');
    Route::delete('/variants/{variantId}/images/{imageId}', [ProductController::class, 'deleteImage'])->name('deleteImage');
});

// User Management Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// test error page
Route::get('/test-404', function () {
    abort(404);
});

Route::get('/admin/test-404', function () {
    abort(404);
});

Route::get('/test-403', function () {
    abort(403);
});

Route::get('/admin/test-403', function () {
    abort(403);
});
