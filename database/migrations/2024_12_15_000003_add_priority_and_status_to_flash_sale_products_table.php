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
        if (Schema::hasTable('flash_sale_products')) {
            Schema::table('flash_sale_products', function (Blueprint $table) {
                if (!Schema::hasColumn('flash_sale_products', 'priority')) {
                    $table->integer('priority')->default(0)->after('remaining_stock')->comment('Thứ tự ưu tiên hiển thị (càng cao càng ưu tiên)');
                }
                if (!Schema::hasColumn('flash_sale_products', 'status')) {
                    $table->enum('status', ['active', 'inactive', 'featured'])->default('active')->after('priority')->comment('Trạng thái sản phẩm: active, inactive, featured');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('flash_sale_products')) {
            Schema::table('flash_sale_products', function (Blueprint $table) {
                if (Schema::hasColumn('flash_sale_products', 'priority')) {
                    $table->dropColumn('priority');
                }
                if (Schema::hasColumn('flash_sale_products', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    }
};
