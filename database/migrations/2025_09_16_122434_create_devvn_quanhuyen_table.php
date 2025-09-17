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
        Schema::create('devvn_quanhuyen', function (Blueprint $table) {
            $table->id();
            $table->string('maqh', 10)->unique();
            $table->string('name');
            $table->string('matp', 10);
            $table->timestamps();
            
            $table->index('matp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devvn_quanhuyen');
    }
};
