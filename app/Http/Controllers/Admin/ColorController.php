<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::latest()->paginate(10);
        return view('layouts.admin.color.list', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.admin.color.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'unique:colors',
                'regex:/^[a-zA-ZÀ-ỹ\s]+$/', // Chỉ cho phép chữ cái và khoảng trắng
            ],
        ], [
            'name.required' => 'Tên màu là bắt buộc.',
            'name.string' => 'Tên màu phải là chuỗi ký tự.',
            'name.min' => 'Tên màu phải có ít nhất 2 ký tự.',
            'name.max' => 'Tên màu không được vượt quá 50 ký tự.',
            'name.unique' => 'Tên màu này đã tồn tại.',
            'name.regex' => 'Tên màu chỉ được chứa chữ cái và khoảng trắng, không được chứa số hoặc ký tự đặc biệt.',
        ]);

        // Sanitize và format tên màu
        $colorName = trim($request->name);
        $colorName = ucwords(strtolower($colorName)); // Viết hoa chữ cái đầu

        Color::create(['name' => $colorName]);

        return redirect()->route('admin.colors.index')
            ->with('success', 'Màu sắc đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return view('layouts.admin.color.update', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
        ]);

        // Kiểm tra xem dữ liệu có thay đổi không
        if ($color->name === $request->name) {
            return redirect()->route('admin.colors.index')
                ->with('info', 'Không có dữ liệu nào được thay đổi!');
        }

        // Nếu có thay đổi thì mới cập nhật
        $color->update($request->all());

        return redirect()->route('admin.colors.index')
            ->with('success', 'Màu sắc đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color->delete();

        return redirect()->route('admin.colors.index')
            ->with('success', 'Màu sắc đã được xóa thành công!');
    }
}
