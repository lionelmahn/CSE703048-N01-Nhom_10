<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            // Drop foreign key first if it exists
            $table->dropForeign(['he_dao_tao_id']);
            // Drop the column
            $table->dropColumn('he_dao_tao_id');
        });
    }

    public function down(): void
    {
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            // Restore the column if rollback is needed
            $table->unsignedBigInteger('he_dao_tao_id')->after('loai_hinh_dao_tao_id')->nullable();
            $table->foreign('he_dao_tao_id')->references('id')->on('he_dao_tao')->nullOnDelete();
        });
    }
};
