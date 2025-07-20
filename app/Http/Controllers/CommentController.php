<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $blogId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'blog_id' => $blogId,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id ?? null,
        ]);

        return back()->with('success', 'Bình luận đã được gửi!');
    }

    public function reply(Request $request, $parentId)
    {
        $request->validate(['content' => 'required']);

        $parentComment = Comment::findOrFail($parentId);

        Comment::create([
            'user_id' => auth()->id(),
            'blog_id' => $parentComment->blog_id, // Lấy blog_id từ comment cha
            'content' => $request->content,
            'parent_id' => $parentId,
        ]);

        return back()->with('success', 'Phản hồi đã được gửi!');
    }
}
