<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function sanpham(Request $request)
    {
        $barFilter = $request->get('bar_filter', 'year');
        $totalProducts = Product::count();
        // Sản phẩm bán chạy nhất (theo tổng số lượng đã bán)
        $bestSeller = OrderDetail::select('product_variant_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_variant_id')
            ->orderByDesc('total_sold')
            ->first();
        $bestSellerName = $bestSeller ? optional(optional(ProductVariant::find($bestSeller->product_variant_id))->product)->name : null;
        // Sản phẩm sắp hết hàng (quantity <= 5)
        $lowStockCount = ProductVariant::where('quantity', '<=', 5)->where('quantity', '>', 0)->count();
        // Sản phẩm hết hàng
        $outOfStockCount = ProductVariant::where('quantity', 0)->count();
        // Sản phẩm chưa được mua lần nào
        $soldVariantIds = OrderDetail::pluck('product_variant_id')->unique();
        $neverSoldCount = ProductVariant::whereNotIn('id', $soldVariantIds)->count();
        // Sản phẩm đang giảm giá
        $discountCount = ProductVariant::whereColumn('price_sale', '<', 'price')->count();

        // Dữ liệu cho biểu đồ cột: Top 5 sản phẩm bán chạy theo filter
        $topProductsQuery = OrderDetail::select('product_variant_id', DB::raw('SUM(quantity) as total_sold'));
        if ($barFilter === 'day') {
            $topProductsQuery->whereDate('created_at', now()->toDateString());
        } elseif ($barFilter === 'month') {
            $topProductsQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } else { // year
            $topProductsQuery->whereYear('created_at', now()->year);
        }
        $topProducts = $topProductsQuery->groupBy('product_variant_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
        $barLabels = $topProducts->map(function($item){
            return optional(optional(ProductVariant::find($item->product_variant_id))->product)->name;
        });
        $barData = $topProducts->pluck('total_sold');

        // Dữ liệu cho biểu đồ tròn: Tỷ lệ sản phẩm còn hàng/hết hàng
        $stock = ProductVariant::select('product_id', DB::raw('SUM(quantity) as stock'))
            ->groupBy('product_id')
            ->get();
        $inStock = $stock->where('stock', '>', 0)->count();
        $outStock = $stock->where('stock', '=', 0)->count();

        return view('layouts.admin.thongke.sanpham', compact(
            'totalProducts', 'bestSellerName', 'lowStockCount', 'outOfStockCount', 'neverSoldCount', 'discountCount',
            'barLabels', 'barData', 'inStock', 'outStock', 'barFilter'
        ));
    }

    public function nguoidung()
    {
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        $newUsersMonth = User::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        // Người dùng mua hàng nhiều nhất
        $topBuyer = Order::select('user_id', DB::raw('COUNT(*) as order_count'))
            ->where('user_id', '>', 0)
            ->groupBy('user_id')
            ->orderByDesc('order_count')
            ->first();
        $topBuyerName = $topBuyer ? optional(User::find($topBuyer->user_id))->name : null;
        // Phân loại vai trò
        $adminCount = User::whereHas('role', function($q){ $q->where('name', 'admin'); })->count();
        $staffCount = User::whereHas('role', function($q){ $q->where('name', 'staff'); })->count();
        $customerCount = User::whereHas('role', function($q){ $q->where('name', 'customer'); })->count();
        return view('layouts.admin.thongke.nguoidung', compact(
            'totalUsers', 'newUsersToday', 'newUsersMonth', 'topBuyerName', 'adminCount', 'staffCount', 'customerCount'
        ));
    }

    public function yeuthich()
    {
        // Sản phẩm được thích nhiều nhất
        $mostLiked = Favorite::select('product_id', DB::raw('COUNT(*) as like_count'))
            ->groupBy('product_id')
            ->orderByDesc('like_count')
            ->first();
        $mostLikedProductName = $mostLiked ? optional(Product::find($mostLiked->product_id))->name : null;
        // Thống kê lượt thích theo từng sản phẩm
        $productStats = Product::withCount('favorites')->get()->map(function($product) {
            return [
                'name' => $product->name,
                'likes' => $product->favorites_count,
                'avg_rating' => '-', // Không có dữ liệu đánh giá
                'review_count' => 0, // Không có dữ liệu đánh giá
            ];
        });
        // Sản phẩm được đánh giá cao nhất: không có dữ liệu
        $topRatedProductName = '-';
        return view('layouts.admin.thongke.yeuthich', compact(
            'mostLikedProductName', 'topRatedProductName', 'productStats'
        ));
    }

    public function donhang()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 0)->count(); // Chưa xử lý
        $processedOrders = Order::where('status', 1)->count(); // Đã xử lý
        $totalProductsSold = OrderDetail::sum('quantity');
        return view('layouts.admin.thongke.donhang', compact(
            'totalOrders', 'pendingOrders', 'processedOrders', 'totalProductsSold'
        ));
    }
} 