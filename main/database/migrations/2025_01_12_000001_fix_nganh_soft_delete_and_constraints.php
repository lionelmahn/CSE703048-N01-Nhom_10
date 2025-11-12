<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('nganh', 'active')) {
            Schema::table('nganh', function (Blueprint $table) {
                $table->boolean('active')->default(true)->after('ten');
            });
        }

        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['nganh_id']);
            
            // Add new foreign key with RESTRICT (cannot delete if CTDTs exist)
            $table->foreign('nganh_id')
                  ->references('id')
                  ->on('nganh')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        // Revert to cascade delete
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            $table->dropForeign(['nganh_id']);
            $table->foreign('nganh_id')->references('id')->on('nganh')->cascadeOnDelete();
        });

        // Remove active column
        if (Schema::hasColumn('nganh', 'active')) {
            Schema::table('nganh', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }
};
