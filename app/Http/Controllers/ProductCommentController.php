<?php

namespace App\Http\Controllers;

use App\Models\ProductComment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCommentController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'parent_id' => 'nullable|exists:product_comments,id',
        ]);

        $product = Product::findOrFail($productId);

        ProductComment::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'rating' => $request->rating,
            'parent_id' => $request->parent_id ?? null,
        ]);

        return back()->with('success', 'Bình luận đã được gửi thành công!');
    }

    public function destroy($id)
    {
        $comment = ProductComment::findOrFail($id);
        
        // Chỉ cho phép xóa bình luận của chính mình hoặc admin
        if (Auth::id() !== $comment->user_id && !Auth::user()->role->name === 'admin') {
            return back()->with('error', 'Bạn không có quyền xóa bình luận này!');
        }

        $comment->delete();
        return back()->with('success', 'Bình luận đã được xóa!');
    }

    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $parentComment = ProductComment::findOrFail($commentId);
        
        ProductComment::create([
            'product_id' => $parentComment->product_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $commentId,
        ]);

        return back()->with('success', 'Trả lời đã được gửi thành công!');
    }
}
