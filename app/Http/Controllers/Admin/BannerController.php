<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;


class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
        return view('layouts.admin.banner.list', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('layouts.admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/banners'), $imageName);
        }

        Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'img' => $imageName ?? null,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Tạo banner thành công.');
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
    public function edit(Banner $banner)
    {
        return view('layouts.admin.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('img')) {
            // Delete old image
            if ($banner->img && file_exists(public_path('images/banners/' . $banner->img))) {
                unlink(public_path('images/banners/' . $banner->img));
            }
            
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/banners'), $imageName);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'img' => $imageName ?? $banner->img,
            'is_active' => $request->has('is_active') ? true : false
        ];

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Cập nhật banner thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        if ($banner->img && file_exists(public_path('images/banners/' . $banner->img))) {
            unlink(public_path('images/banners/' . $banner->img));
        }
        
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Xóa banner thành công.');
    }

    public function updateStatus(Request $request, Banner $banner)
    {
        try {
            $banner->update([
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật trạng thái thất bại'
            ], 500);
        }
    }
}
