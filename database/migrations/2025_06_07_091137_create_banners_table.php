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
        Schema::create('banners', function (Blueprint $table) {
            $table->id(); // ID tự tăng
            $table->string('img'); // Đường dẫn ảnh banner
            $table->string('title'); // Tiêu đề
            $table->text('description')->nullable(); // Mô tả (có thể null)
            $table->boolean('is_active')->default(true); // Trạng thái hiển thị
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
