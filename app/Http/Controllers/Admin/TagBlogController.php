<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagBlog;
use Illuminate\Http\Request;

class TagBlogController extends Controller
{
    public function index()
    {
        $tagBlogs = TagBlog::query()->latest()->paginate(10);
        return view('layouts.admin.tag-blogs.index', compact('tagBlogs'));
    }

    public function create()
    {
        return view('layouts.admin.tag-blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_tag' => 'required|string|max:255|unique:tag_blog,name_tag',
            'content' => 'nullable|string',
        ]);

        TagBlog::create($request->all());

        return redirect()->route('admin.tag-blogs.index')->with('success', 'Thẻ được tạo thành công.');
    }

    public function show(TagBlog $tagBlog)
    {
        return view('layouts.admin.tag-blogs.show', compact('tagBlog'));
    }

    public function edit(TagBlog $tagBlog)
    {
        return view('layouts.admin.tag-blogs.edit', compact('tagBlog'));
    }

    public function update(Request $request, TagBlog $tagBlog)
    {
        $request->validate([
            'name_tag' => 'required|string|max:255|unique:tag_blog,name_tag,' . $tagBlog->id,
            'content' => 'nullable|string',
        ]);

        $tagBlog->update($request->all());

        return redirect()->route('admin.tag-blogs.index')->with('success', 'Thẻ được cập nhật thành công.');
    }

    public function destroy(TagBlog $tagBlog)
    {
        $tagBlog->delete();
        return back()->with('success', 'Thẻ đã được xóa thành công.');
    }
} 