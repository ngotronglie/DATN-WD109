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
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id');
            $table->string('bank_name')->nullable();;
            $table->string('bank_number')->nullable();;
            $table->text('reason');
            $table->string('image')->nullable();
            $table->timestamp('refund_requested_at')->nullable(); // Thời gian yêu cầu
            $table->timestamp('refund_completed_at')->nullable(); // Thời gian hoàn tiền
            $table->string('refunded_by')->nullable();            // Người hoàn tiền
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
