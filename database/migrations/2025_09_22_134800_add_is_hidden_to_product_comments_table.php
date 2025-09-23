<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_comments', function (Blueprint $table) {
            if (!Schema::hasColumn('product_comments', 'is_hidden')) {
                $table->boolean('is_hidden')->default(false)->after('content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_comments', function (Blueprint $table) {
            if (Schema::hasColumn('product_comments', 'is_hidden')) {
                $table->dropColumn('is_hidden');
            }
        });
    }
};
