<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('sale_price', 10, 0);
            $table->integer('sale_quantity');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('trang_thai', ['kích_hoạt', 'vô_hiệu_hóa'])->default('kích_hoạt');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_sales');
    }
};
