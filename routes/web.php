<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CapacityController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BannerController;
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
