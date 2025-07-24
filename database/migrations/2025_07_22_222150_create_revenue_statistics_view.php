<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateRevenueStatisticsView extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW revenue_statistics AS
            SELECT 
                DATE(o.created_at) AS order_date,
                p.name AS product_name,
                SUM(od.quantity) AS total_quantity,
                SUM(od.quantity * od.price) AS total_revenue
            FROM order_detail od
            JOIN orders o ON o.id = od.order_id
            JOIN product_variants pv ON pv.id = od.product_variant_id
            JOIN products p ON p.id = pv.product_id
            WHERE o.status = 1
            GROUP BY order_date, product_name
            ORDER BY order_date DESC
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS revenue_statistics");
    }
};
