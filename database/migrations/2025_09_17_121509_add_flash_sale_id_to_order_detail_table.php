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
        Schema::table('order_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('order_detail', 'flash_sale_id')) {
                $table->unsignedBigInteger('flash_sale_id')->nullable()->after('product_variant_id');
                $table->foreign('flash_sale_id')
                    ->references('id')->on('flash_sales')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_detail', function (Blueprint $table) {
            if (Schema::hasColumn('order_detail', 'flash_sale_id')) {
                $table->dropForeign(['flash_sale_id']);
                $table->dropColumn('flash_sale_id');
            }
        });
    }
};
