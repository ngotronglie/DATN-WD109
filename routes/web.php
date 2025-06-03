<?php

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

Route::get('/admin', function(){
    return view('layouts.admin.index');
});


/*
categories admin
*/

Route::get('/admin/categories', function(){
    return view('layouts.admin.category.list');
});

Route::get('/admin/categories/create', function(){
    return view('layouts.admin.category.create');
});

Route::get('/admin/categories/update', function(){
    return view('layouts.admin.category.update');
});

// end route admin
