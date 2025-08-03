<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueStatisticController extends Controller
{
    public function index(Request $request)
    {
        // Chuẩn hóa ngày đầu vào
        $from = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $to = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        // Truy vấn thống kê tổng đơn hàng thành công
        $query = DB::table('orders as o')
            ->select(
                DB::raw('SUM(od.quantity * od.price) as total_revenue'),
                DB::raw('SUM(od.quantity) as total_products_sold'), // chỉ tính sản phẩm trong đơn thành công
                DB::raw('COUNT(DISTINCT o.id) as total_success_orders'),
                DB::raw('COUNT(DISTINCT o.user_id) as total_customers')
            )
            ->join('order_detail as od', 'o.id', '=', 'od.order_id')
            ->where('o.status', 5); // chỉ tính đơn hàng thành công

        // Nếu người dùng chọn ngày lọc
        if ($from && $to) {
            $query->whereBetween(DB::raw('DATE(o.created_at)'), [$from, $to]);
        }

        $stat = $query->first(); // chỉ lấy 1 dòng kết quả

        // Lấy danh sách đơn thành công chi tiết để hiển thị bên dưới
        $ordersQuery = DB::table('orders as o')
            ->join('users as u', 'o.user_id', '=', 'u.id')
            ->select('o.id', 'o.order_code', 'o.total_amount', 'o.created_at', 'u.name as user_name')
            ->where('o.status', 5);

        if ($from && $to) {
            $ordersQuery->whereBetween(DB::raw('DATE(o.created_at)'), [$from, $to]);
        }

        $orders = $ordersQuery->orderByDesc('o.created_at')->paginate(5);

        $dailyRevenue = DB::table('orders as o')
            ->join('order_detail as od', 'o.id', '=', 'od.order_id')
            ->select(
                DB::raw('DATE(o.created_at) as date'),
                DB::raw('SUM(od.quantity * od.price) as revenue')
            )
            ->where('o.status', 5)
            ->groupBy(DB::raw('DATE(o.created_at)'))
            ->orderBy('date')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween(DB::raw('DATE(o.created_at)'), [$from, $to]);
            })
            ->get();

        $lowStockProducts = DB::table('product_variants as pv')
            ->join('products as p', 'pv.product_id', '=', 'p.id')
            ->where('pv.quantity', '<', 10)
            ->select('pv.id as variant_id', 'p.name as product_name', 'pv.quantity')
            ->orderBy('pv.quantity', 'asc')
            ->get();



        return view('layouts.admin.statistics.index', compact('stat', 'orders', 'dailyRevenue', 'lowStockProducts'));
    }
}
