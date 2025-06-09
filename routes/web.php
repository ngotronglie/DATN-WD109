<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RoleController;
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
