<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flash_sale_products', function (Blueprint $table) {
            $table->id(); // Tự động tạo khóa chính AUTO_INCREMENT
            $table->unsignedBigInteger('flash_sale_id'); // Khóa ngoại tới bảng flash_sales
            $table->unsignedBigInteger('product_variant_id'); // Khóa ngoại tới bảng product_variants
            $table->decimal('original_price', 12, 2)->comment('Giá gốc sản phẩm');
            $table->decimal('sale_price', 12, 2)->comment('Giá sản phẩm trong Flash Sale');
            $table->integer('initial_stock')->default(0)->comment('Số lượng ban đầu của sản phẩm');
            $table->integer('remaining_stock')->default(0)->comment('Số lượng còn lại của sản phẩm');
            $table->integer('sold_quantity')->default(0)->comment('Số lượng đã bán');
            $table->timestamps(); // Tạo created_at và updated_at

            // Định nghĩa khóa ngoại
            $table->foreign('flash_sale_id')->references('id')->on('flash_sales')->cascadeOnDelete();
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale_products');
    }
}
