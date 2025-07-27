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
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id(); // Tự động thêm khóa chính (id, bigInteger)
            $table->string('name'); // Tên của flash sale
            $table->dateTime('start_time'); // Thời gian bắt đầu
            $table->dateTime('end_time'); // Thời gian kết thúc
            $table->boolean('is_active')->default(true); // Trạng thái (mặc định là active)
            $table->timestamps(); // Cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
