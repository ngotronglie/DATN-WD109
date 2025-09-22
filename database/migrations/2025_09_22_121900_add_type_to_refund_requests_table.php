<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            // Thêm cột type để phân biệt luồng 'return' (khách yêu cầu) và 'admin_refund' (admin khởi tạo)
            if (!Schema::hasColumn('refund_requests', 'type')) {
                $table->string('type')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            if (Schema::hasColumn('refund_requests', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
