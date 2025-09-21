<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\TagBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with('user', 'tags')->latest()->paginate(10);
        return view('layouts.admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = TagBlog::query()->get();
        return view('layouts.admin.blog.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tag_blog,id',
        ]);

        $data = $request->except('image', 'tag_ids');
        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->has('is_active');
        $data['slug'] = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/blogs');
            $data['image'] = Storage::url($path);
        }

        $blog = Blog::create($data);

        if ($request->has('tag_ids')) {
            $blog->tags()->sync($request->tag_ids);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function edit(Blog $blog)
    {
        $tags = TagBlog::query()->get();
        return view('layouts.admin.blogs.edit', compact('blog', 'tags'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tag_blog,id',
        ]);

        $data = $request->except('image', 'tag_ids');
        $data['is_active'] = $request->has('is_active');
        $data['slug'] = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($blog->image) {
                Storage::delete(str_replace('/storage', 'public', $blog->image));
            }
            $path = $request->file('image')->store('public/images/blogs');
            $data['image'] = Storage::url($path);
        }

        $blog->update($data);

        $blog->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('admin.blogs.index')->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::delete(str_replace('/storage', 'public', $blog->image));
        }
        $blog->tags()->detach();
        $blog->delete();
        return back()->with('success', 'Bài viết đã được xóa thành công.');
    }
}
