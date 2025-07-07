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
        $blogs = \App\Models\Blog::with('user')
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
        $blog = \App\Models\Blog::with(['user', 'tags', 'comments.user', 'comments.replies.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Lấy các blog gần đây
        $recentBlogs = Blog::with('user')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Lấy tất cả tags
        $tags = TagBlog::withCount('blogs')->get();

        return view('layouts.user.blogDetail', compact('blog', 'recentBlogs', 'tags'));
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
   /**
     * Hiển thị form chỉnh sửa blog (chỉ admin)
     */
    public function edit($slug)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }
        
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $tags = TagBlog::all();
        
        return view('layouts.user.blog.edit', compact('blog', 'tags'));
    }

     /**
     * Cập nhật blog (chỉ admin)
     */
    public function update(Request $request, $slug)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }
        
        $blog = Blog::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tag_blog,id',
        ]);

        $data = $request->except('image', 'tag_ids');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($blog->image) {
                \Illuminate\Support\Facades\Storage::delete(str_replace('/storage', 'public', $blog->image));
            }
            $path = $request->file('image')->store('public/images/blogs');
            $data['image'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $blog->update($data);
        $blog->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('blog.detail.show', $blog->slug)->with('success', 'Bài viết đã được cập nhật thành công.');
    }
 /**
     * Xóa blog (chỉ admin)
     */
    public function destroy($slug)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }
        
        $blog = Blog::where('slug', $slug)->firstOrFail();
        
        if ($blog->image) {
            \Illuminate\Support\Facades\Storage::delete(str_replace('/storage', 'public', $blog->image));
        }
        
        $blog->tags()->detach();
        $blog->delete();
        
        return redirect()->route('blog.detail.index')->with('success', 'Bài viết đã được xóa thành công.');
    }

    /**
     * Tìm kiếm blog theo tag
     */
    public function searchByTag($tagId)
    {
        $tag = TagBlog::findOrFail($tagId);
        $blogs = Blog::with(['user', 'tags'])
            ->where('is_active', true)
            ->whereHas('tags', function($query) use ($tagId) {
                $query->where('tag_blog.id', $tagId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        $recentBlogs = Blog::with('user')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $tags = TagBlog::withCount('blogs')->get();
        
        return view('layouts.user.blog', compact('blogs', 'recentBlogs', 'tags', 'tag'));
    }
     /**
     * Tìm kiếm blog theo từ khóa
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        
        $blogs = Blog::with(['user', 'tags'])
            ->where('is_active', true)
            ->where(function($query) use ($keyword) {
                $query->where('slug', 'like', "%{$keyword}%")
                      ->orWhere('content', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        $recentBlogs = Blog::with('user')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $tags = TagBlog::withCount('blogs')->get();
        
        return view('layouts.user.blog', compact('blogs', 'recentBlogs', 'tags', 'keyword'));
    }
}