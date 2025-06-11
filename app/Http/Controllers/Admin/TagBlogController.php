<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagBlog;
use App\Http\Requests\Admin\TagBlogRequest;

class TagBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tags = TagBlog::latest()->paginate(5);
            return view('admin.tag-blogs.list', compact('tags'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tag-blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagBlogRequest $request)
    {
        try {
            TagBlog::create($request->validated());
            return redirect()->route('admin.tag-blogs.index')
                ->with('success', 'Tag blog đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo tag blog: ' . $e->getMessage());
        }
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
    public function edit(TagBlog $tagBlog)
    {
        return view('admin.tag-blogs.update', compact('tagBlog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagBlogRequest $request, TagBlog $tagBlog)
    {
        try {
            $tagBlog->update($request->validated());
            return redirect()->route('admin.tag-blogs.index')
                ->with('success', 'Tag blog đã được cập nhật thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật tag blog: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TagBlog $tagBlog)
    {
        try {
            $tagBlog->delete();
            return redirect()->route('admin.tag-blogs.index')
                ->with('success', 'Tag blog đã được xóa thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa tag blog: ' . $e->getMessage());
        }
    }
}
