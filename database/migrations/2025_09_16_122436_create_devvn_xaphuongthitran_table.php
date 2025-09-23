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
        Schema::create('devvn_xaphuongthitran', function (Blueprint $table) {
            $table->id();
            $table->string('xaid', 10)->unique();
            $table->string('name');
            $table->string('maqh', 10);
            $table->timestamps();
            
            $table->index('maqh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devvn_xaphuongthitran');
    }
};
