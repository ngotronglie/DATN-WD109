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
    
}