<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VoucherController;

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

// Routes cho quản lý Voucher

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('vouchers', VoucherController::class);
});

// end route admin
