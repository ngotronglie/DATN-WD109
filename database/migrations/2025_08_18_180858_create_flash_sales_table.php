<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashSalesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id(); // Tự động tạo cột "id" AUTO_INCREMENT
            $table->string('title', 255)->comment('Tiêu đề Flash Sale');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->dateTime('start_time')->comment('Thời gian bắt đầu');
            $table->dateTime('end_time')->comment('Thời gian kết thúc');
            $table->enum('status', ['scheduled', 'active', 'inactive'])
                  ->default('scheduled')->comment('Trạng thái');
            $table->timestamps(); // Tạo "created_at" và "updated_at"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
}
