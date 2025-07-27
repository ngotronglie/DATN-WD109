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
        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id(); // ID chính (khoá tự tăng)

            $table->unsignedBigInteger('flash_sale_id'); // Khoá ngoại đến bảng flash_sales
            $table->unsignedBigInteger('product_variant_id'); // Khoá ngoại đến bảng product_variants

            $table->decimal('flash_price', 15, 2); // Giá bán trong flash sale
            $table->unsignedInteger('quantity_limit')->default(0); // Số lượng tối đa được bán
            $table->unsignedInteger('sold_quantity')->default(0); // Số lượng đã bán

            $table->timestamps(); // Các cột thời gian created_at và updated_at

            // Khoá ngoại
            $table->foreign('flash_sale_id')
                ->references('id')->on('flash_sales')
                ->onDelete('cascade'); // Xoá flash_sale => xoá flash_sale_items

            $table->foreign('product_variant_id')
                ->references('id')->on('product_variants')
                ->onDelete('cascade'); // Xoá product_variant => xoá flash_sale_items

            // Tạo unique để đảm bảo không có flash_sale_id + product_variant_id trùng nhau
            $table->unique(['flash_sale_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};
