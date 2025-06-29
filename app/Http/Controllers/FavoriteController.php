<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm yêu thích của user
     */
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with(['product.category', 'product.variants'])->get();
        
        return view('layouts.user.favorite', compact('favorites'));
    }

    /**
     * Thêm sản phẩm vào danh sách yêu thích
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        // Kiểm tra xem sản phẩm đã có trong favorites chưa
        $existingFavorite = $user->favorites()->where('product_id', $productId)->first();
        
        if ($existingFavorite) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã có trong danh sách yêu thích!'
            ], 400);
        }

        // Tạo favorite mới
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào danh sách yêu thích!',
            'favorite' => $favorite->load('product')
        ]);
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $favorite = $user->favorites()->findOrFail($id);

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích!'
        ]);
    }

    /**
     * Kiểm tra xem sản phẩm có trong favorites của user không
     */
    public function checkFavorite($productId)
    {
        $user = Auth::user();
        $isFavorite = $user->favorites()->where('product_id', $productId)->exists();

        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }

    /**
     * Lấy số lượng sản phẩm yêu thích của user
     */
    public function getFavoriteCount()
    {
        $user = Auth::user();
        $count = $user->favorites()->count();

        return response()->json([
            'count' => $count
        ]);
    }
}
