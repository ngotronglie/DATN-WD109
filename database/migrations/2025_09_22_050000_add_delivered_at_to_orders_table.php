<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'delivered_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('delivered_at')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'delivered_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('delivered_at');
            });
        }
    }
};
