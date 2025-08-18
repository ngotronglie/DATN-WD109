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
            $table->id(); // Tự động tạo cột id AUTO_INCREMENT
            $table->foreignId('flash_sale_id') // Cột khóa ngoại flash_sale_id
                  ->constrained('flash_sales')
                  ->cascadeOnDelete(); // Xóa flash_sale thì xóa luôn bản ghi liên quan

            $table->foreignId('product_id') // Cột khóa ngoại product_id
                  ->constrained('products')
                  ->cascadeOnDelete(); // Xóa product thì xóa luôn bản ghi liên quan

            $table->decimal('original_price', 12, 2); // Giá gốc
            $table->decimal('sale_price', 12, 2); // Giá bán
            $table->integer('discount_percent')->virtualAs(
                'ROUND((100 - (sale_price / original_price * 100)), 0)'
            ); // Tính toán tự động

            $table->integer('initial_stock'); // Số lượng hàng ban đầu
            $table->integer('remaining_stock'); // Số lượng còn lại
            $table->integer('sold_quantity')->default(0); // Số lượng đã bán

            $table->timestamps(); // Tự động thêm created_at và updated_at
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
