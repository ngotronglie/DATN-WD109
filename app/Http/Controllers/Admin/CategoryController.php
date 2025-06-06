<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        return view('layouts.admin.category.list');
    }

    public function create() {
        return view('layouts.admin.category.create');
    }

    public function update() {
        return view('layouts.admin.category.update');
    }
}
