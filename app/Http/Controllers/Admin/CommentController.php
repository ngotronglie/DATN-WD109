<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Hiển thị danh sách bình luận
    public function index()
    {
        $comments = Comment::with(['blog', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('layouts.admin.comments.index', compact('comments'));
    }

    // Xóa bình luận
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Đã xóa bình luận thành công!');
    }

    // Ẩn/Hiện bình luận
    public function toggleVisibility(Comment $comment)
    {
        $comment->is_hidden = !$comment->is_hidden;
        $comment->save();
        return back()->with('success', $comment->is_hidden ? 'Đã ẩn bình luận.' : 'Đã hiện bình luận.');
    }
}
