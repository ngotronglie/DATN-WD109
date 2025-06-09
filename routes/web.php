<?php
use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
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
Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
    Route::view('/', 'layouts.admin.category.list')->name('index');
    Route::view('/create', 'layouts.admin.category.create')->name('create');
    Route::view('/update', 'layouts.admin.category.update')->name('update');
});

// Nhóm routes cho quản lý banners
// Route::prefix('admin/banners')->name('admin.banners.')->group(function () {
//     Route::view('/', 'layouts.admin.banner.list')->name('index');
//     Route::view('/create', 'layouts.admin.banner.create')->name('create');
//     Route::view('/update', 'layouts.admin.banner.update')->name('update');
// });


 Route::prefix('admin/banners')->name('admin.banners.')->group(function () {
    Route::get('/', [BannerController::class, 'index'])->name('index');
    Route::get('/create', [BannerController::class, 'create'])->name('create');
    Route::get('/update', [BannerController::class, 'update'])->name('update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::post('banners/{banner}/status', [\App\Http\Controllers\Admin\BannerController::class, 'updateStatus'])->name('banners.status');
});
