<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('flash_sale_products', function (Blueprint $table) {
            // Thêm cột priority (ưu tiên sắp xếp) và status (trạng thái) nếu chưa tồn tại
            if (!Schema::hasColumn('flash_sale_products', 'priority')) {
                $table->unsignedInteger('priority')->default(0)->after('original_price');
            }
            if (!Schema::hasColumn('flash_sale_products', 'status')) {
                // Dùng string để linh hoạt, mặc định 'active'
                $table->string('status', 20)->default('active')->after('priority');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flash_sale_products', function (Blueprint $table) {
            // Xóa các cột khi rollback nếu tồn tại
            if (Schema::hasColumn('flash_sale_products', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('flash_sale_products', 'priority')) {
                $table->dropColumn('priority');
            }
        });
    }
};
