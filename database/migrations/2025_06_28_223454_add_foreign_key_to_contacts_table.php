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
        Schema::table('contacts', function (Blueprint $table) {
            // Kiểm tra xem foreign key đã tồn tại chưa
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('contacts');
            
            $foreignKeyExists = false;
            foreach ($foreignKeys as $foreignKey) {
                if (in_array('user_id', $foreignKey->getLocalColumns())) {
                    $foreignKeyExists = true;
                    break;
                }
            }
            
            // Chỉ tạo foreign key nếu chưa tồn tại
            if (!$foreignKeyExists) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Kiểm tra xem foreign key có tồn tại không trước khi xóa
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('contacts');
            
            $foreignKeyExists = false;
            foreach ($foreignKeys as $foreignKey) {
                if (in_array('user_id', $foreignKey->getLocalColumns())) {
                    $foreignKeyExists = true;
                    break;
                }
            }
            
            if ($foreignKeyExists) {
                $table->dropForeign(['user_id']);
            }
        });
    }
};
