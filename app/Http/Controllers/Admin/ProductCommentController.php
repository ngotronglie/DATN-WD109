<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductComment;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function index()
    {
        $comments = ProductComment::with(['product', 'user', 'replies'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('layouts.admin.product-comments.index', compact('comments'));
    }

    public function destroy($id)
    {
        $comment = ProductComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Bình luận đã được xóa thành công!');
    }

    public function show($id)
    {
        $comment = ProductComment::with(['product', 'user', 'replies.user'])->findOrFail($id);
        return view('layouts.admin.product-comments.show', compact('comment'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $parentComment = ProductComment::findOrFail($id);
        
        ProductComment::create([
            'product_id' => $parentComment->product_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $id,
        ]);

        return redirect()->back()->with('success', 'Trả lời đã được gửi thành công!');
    }
}
