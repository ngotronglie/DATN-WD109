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
        Schema::create('flash_sale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('sale_price', 10, 2);
            $table->integer('sale_quantity');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('trang_thai', ['kích_hoạt', 'tạm_dừng'])->default('kích_hoạt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale');
    }
};
