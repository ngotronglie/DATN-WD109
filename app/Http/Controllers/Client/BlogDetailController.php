<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\TagBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BlogDetailController extends Controller
{
    /**
     * Hiển thị danh sách tất cả blog
     */
    public function index()
    {
        $blogs = Blog::with(['user', 'tags'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        $recentBlogs = Blog::with('user')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $tags = TagBlog::withCount('blogs')->get();
        
        return view('layouts.user.blog', compact('blogs', 'recentBlogs', 'tags'));
    }
      /**
     * Hiển thị chi tiết một blog
     */
    public function show($slug)
    {
        $blog = Blog::with(['user', 'tags'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        // Tăng lượt xem
        $blog->increment('view');
        
        // Lấy blog liên quan
        $relatedBlogs = Blog::with('user')
            ->where('is_active', true)
            ->where('id', '!=', $blog->id)
            ->whereHas('tags', function($query) use ($blog) {
                $query->whereIn('tag_blog.id', $blog->tags->pluck('id'));
            })
            ->orWhere('user_id', $blog->user_id)
            ->limit(3)
            ->get();
            
        // Lấy blog gần đây
        $recentBlogs = Blog::with('user')
            ->where('is_active', true)
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Lấy tất cả tags
        $tags = TagBlog::withCount('blogs')->get();
        
        return view('layouts.user.blogDetail', compact('blog', 'relatedBlogs', 'recentBlogs', 'tags'));
    }
    /**
     * Hiển thị form tạo blog mới (chỉ admin)
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }
        
        $tags = TagBlog::all();
        return view('layouts.user.blog.create', compact('tags'));
    }
    /**
    * Lưu blog mới (chỉ admin)
    */
   public function store(Request $request)
   {
       if (!Auth::check() || !Auth::user()->is_admin) {
           abort(403);
       }
       
       $request->validate([
           'slug' => 'required|string|max:255|unique:blogs,slug',
           'content' => 'required|string',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           'tag_ids' => 'nullable|array',
           'tag_ids.*' => 'exists:tag_blog,id',
       ]);

       $data = $request->except('image', 'tag_ids');
       $data['user_id'] = Auth::id();
       $data['is_active'] = $request->has('is_active');

       if ($request->hasFile('image')) {
           $path = $request->file('image')->store('public/images/blogs');
           $data['image'] = \Illuminate\Support\Facades\Storage::url($path);
       }

       $blog = Blog::create($data);

       if ($request->has('tag_ids')) {
           $blog->tags()->sync($request->tag_ids);
       }

       return redirect()->route('blog.detail.show', $blog->slug)->with('success', 'Bài viết đã được tạo thành công.');
   }

}