<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('flash_sale_id')->nullable()->after('product_variant_id');
            $table->foreign('flash_sale_id')->references('id')->on('flash_sales')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['flash_sale_id']);
            $table->dropColumn('flash_sale_id');
        });
    }
};