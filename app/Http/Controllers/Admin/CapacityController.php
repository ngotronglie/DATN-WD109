<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Capacity;
use Illuminate\Http\Request;

class CapacityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $capacities = Capacity::latest()->paginate(10);
        return view('layouts.admin.capacity.list', compact('capacities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.admin.capacity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:capacities',
        ]);

        // Create new capacity with sanitized data
        $capacity = new Capacity();
        $capacity->name = $request->name;
        $capacity->save();

        return redirect()->route('admin.capacities.index')
            ->with('success', 'Dung lượng đã được thêm thành công!');
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
    public function edit(Capacity $capacity)
    {
        return view('layouts.admin.capacity.update', compact('capacity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Capacity $capacity)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:capacities,name,' . $capacity->id,
        ]);

        // Kiểm tra xem dữ liệu có thay đổi không
        if ($capacity->name === $request->name) {
            return redirect()->route('admin.capacities.index')
                ->with('info', 'Không có dữ liệu nào được thay đổi!');
        }

        // Nếu có thay đổi thì mới cập nhật
        $capacity->name = $request->name;
        $capacity->save();

        return redirect()->route('admin.capacities.index')
            ->with('success', 'Dung lượng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Capacity $capacity)
    {
        $capacity->delete();

        return redirect()->route('admin.capacities.index')
            ->with('success', 'Dung lượng đã được xóa thành công!');
    }
}
