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

    public function donhang(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, today, week, month, year
        
        // Base filtered orders query for all metrics
        $filteredOrders = Order::query();
        
        // Revenue statistics base query (by created_at), and a separate delivered filter by updated_at
        $query = Order::query();
        $deliveredOrdersFiltered = Order::whereIn('status', [5,14,15]);
        
        // Apply time filter
        switch($filter) {
            case 'today':
                $query->whereDate('created_at', today());
                $filteredOrders->whereDate('created_at', today());
                // Delivered today (by updated_at)
                $deliveredOrdersFiltered->whereDate('updated_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $filteredOrders->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $deliveredOrdersFiltered->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                $filteredOrders->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                $deliveredOrdersFiltered->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                $filteredOrders->whereYear('created_at', now()->year);
                $deliveredOrdersFiltered->whereYear('updated_at', now()->year);
                break;
        }

        // Define delivered statuses (considered as successful delivery/completion)
        $deliveredStatuses = [5, 14, 15];

        // Basic order statistics (respect filter)
        $totalOrders = (clone $filteredOrders)->count();
        $pendingOrders = (clone $filteredOrders)->where('status', 0)->count();
        $processedOrders = (clone $filteredOrders)->where('status', 1)->count();

        // Total products sold (respect filter, count delivered-like orders filtered by delivered time)
        $totalProductsSold = OrderDetail::whereIn('order_id', (clone $deliveredOrdersFiltered)->select('id'))
            ->sum('quantity');
        
        // Total revenue: delivered orders filtered by delivered time (updated_at)
        $totalRevenue = (clone $deliveredOrdersFiltered)
            ->sum('total_amount');
            
        // Revenue by payment method
        $revenueByPayment = (clone $deliveredOrdersFiltered)
            ->select('payment_method', DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('payment_method')
            ->get();
            
        // Revenue by order status
        $revenueByStatus = (clone $deliveredOrdersFiltered)
            ->select('status', DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('status')
            ->get();
            
        // Average order value
        $avgOrderValue = (clone $deliveredOrdersFiltered)
            ->avg('total_amount');
            
        // Top customers by revenue
        $topCustomers = (clone $deliveredOrdersFiltered)
            ->where('user_id', '>', 0)
            ->select('user_id', DB::raw('SUM(total_amount) as total_spent'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->with('user')
            ->get();
            
        // Refund statistics (respect filter by order's created_at)
        $totalRefunds = \App\Models\RefundRequest::whereHas('order', function($q) use ($filter) {
            switch($filter) {
                case 'today':
                    $q->whereDate('created_at', today());
                    break;
                case 'week':
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $q->whereYear('created_at', now()->year);
                    break;
            }
        })->count();

        $refundAmount = \App\Models\RefundRequest::whereHas('order', function($q) use ($filter) {
            $q->where('status', 9); // Completed refunds
            switch($filter) {
                case 'today':
                    $q->whereDate('created_at', today());
                    break;
                case 'week':
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $q->whereYear('created_at', now()->year);
                    break;
            }
        })->with('order')->get()->sum('order.total_amount');
        
        // Monthly revenue chart data
        $monthlyRevenue = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('status', 5)
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();
            
        $monthlyLabels = $monthlyRevenue->map(function($item) {
            return Carbon::create($item->year, $item->month)->format('M Y');
        });
        $monthlyData = $monthlyRevenue->pluck('revenue');
        
        // Order status distribution (respect filter)
        $statusDistribution = (clone $filteredOrders)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
            
        $statusLabels = $statusDistribution->map(function($item) {
            $statusNames = [
                0 => 'Chờ xử lý',
                1 => 'Đã xác nhận', 
                2 => 'Đang xử lý',
                3 => 'Đã giao cho vận chuyển',
                4 => 'Đang vận chuyển',
                5 => 'Đã giao hàng',
                6 => 'Đã hủy',
                7 => 'Xác nhận yêu cầu hoàn hàng',
                8 => 'Hoàn hàng',
                9 => 'Hoàn tiền',
                10 => 'Không xác nhận yêu cầu hoàn hàng'
            ];
            return $statusNames[$item->status] ?? 'Không xác định';
        });
        $statusData = $statusDistribution->pluck('count');
        
        return view('layouts.admin.thongke.donhang', compact(
            'totalOrders', 'pendingOrders', 'processedOrders', 'totalProductsSold',
            'totalRevenue', 'revenueByPayment', 'revenueByStatus', 'avgOrderValue',
            'topCustomers', 'totalRefunds', 'refundAmount', 'filter',
            'monthlyLabels', 'monthlyData', 'statusLabels', 'statusData'
        ));
    }

    public function revenueOrders(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, today, week, month, year
        
        // Query orders that contribute to revenue: only delivered successfully
        $query = Order::with(['user', 'orderDetails.product'])
            ->where('status', 5);
        
        // Apply time filter
        switch($filter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        
        // Get orders with pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Calculate total revenue for this filter
        $totalRevenue = $query->clone()->sum('total_amount');
        
        // Get filter options for display
        $filterOptions = [
            'all' => 'Tất cả',
            'today' => 'Hôm nay',
            'week' => 'Tuần này', 
            'month' => 'Tháng này',
            'year' => 'Năm nay'
        ];
        
        return view('layouts.admin.thongke.revenue-orders', compact(
            'orders', 'totalRevenue', 'filter', 'filterOptions'
        ));
    }
} 