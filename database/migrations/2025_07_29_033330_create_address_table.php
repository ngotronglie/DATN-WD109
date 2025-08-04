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
  Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('receiver_name');
            $table->string('phone', 20);
            $table->string('street');   // Số nhà, tên đường
            $table->string('ward');     // Phường/Xã
            $table->string('district'); // Quận/Huyện
            $table->string('city');     // Tỉnh/Thành phố
            $table->boolean('is_default')->default(false); // Địa chỉ mặc định
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
