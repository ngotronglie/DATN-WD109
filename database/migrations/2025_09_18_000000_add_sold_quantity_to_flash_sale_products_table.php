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
            $table->integer('sold_quantity')->default(0)->after('remaining_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flash_sale_products', function (Blueprint $table) {
            $table->dropColumn('sold_quantity');
        });
    }
};
