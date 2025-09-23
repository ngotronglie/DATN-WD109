<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductComment;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    // Danh sách bình luận sản phẩm
    public function index()
    {
        $comments = ProductComment::with(['product', 'user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('layouts.admin.product_comments.index', compact('comments'));
    }

    // Ẩn/hiện bình luận
    public function toggleVisibility(ProductComment $comment)
    {
        $comment->is_hidden = !$comment->is_hidden;
        $comment->save();
        return back()->with('success', $comment->is_hidden ? 'Đã ẩn bình luận.' : 'Đã hiện bình luận.');
    }

    // Xóa bình luận
    public function destroy(ProductComment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Đã xóa bình luận.');
    }
}
