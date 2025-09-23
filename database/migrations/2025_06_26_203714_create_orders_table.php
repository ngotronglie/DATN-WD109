<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('email');
            $table->string('phone');
            $table->text('note')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->boolean('status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('order_code')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->string('status_method')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
