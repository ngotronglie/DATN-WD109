<?php
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\FavoriteController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use Illuminate\Support\Facades\Route;
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


Route::get('/', function () {
    return view('welcome');
});

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
});

// Routes cho quản lý Voucher

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('vouchers', VoucherController::class);
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

// end route admin

// Nhóm routes cho quản lý sản phẩm
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
// Nhóm routes cho quản lý banners
// Route::prefix('admin/banners')->name('admin.banners.')->group(function () {
//     Route::view('/', 'layouts.admin.banner.list')->name('index');
//     Route::view('/create', 'layouts.admin.banner.create')->name('create');
//     Route::view('/update', 'layouts.admin.banner.update')->name('update');
// });
});

 Route::prefix('admin/banners')->name('admin.banners.')->group(function () {
    Route::get('/', [BannerController::class, 'index'])->name('index');
    Route::get('/create', [BannerController::class, 'create'])->name('create');
    Route::get('/update', [BannerController::class, 'update'])->name('update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::post('banners/{banner}/status', [\App\Http\Controllers\Admin\BannerController::class, 'updateStatus'])->name('banners.status');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::view('/', 'layouts.admin.index')->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // Product Management
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('product-variants', ProductVariantController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('capacities', CapacityController::class);

    // Order Management
    Route::resource('orders', OrderController::class);
    Route::resource('carts', CartController::class);

    // Content Management
    Route::resource('blogs', BlogController::class);
    Route::resource('tags', TagController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('banners', BannerController::class);
    Route::post('banners/{banner}/status', [BannerController::class, 'updateStatus'])->name('banners.status');

    // Marketing
    Route::resource('vouchers', VoucherController::class);
    Route::resource('favorites', FavoriteController::class);

    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
