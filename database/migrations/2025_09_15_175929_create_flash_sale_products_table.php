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
        Schema::create('flash_sale_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flash_sale_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->decimal('sale_price', 15, 2);
            $table->integer('sale_quantity');
            $table->integer('initial_stock');
            $table->integer('remaining_stock');
            $table->decimal('original_price', 15, 2);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('flash_sale_id')->references('id')->on('flash_sales')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate product variants in same flash sale
            $table->unique(['flash_sale_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale_products');
    }
};
