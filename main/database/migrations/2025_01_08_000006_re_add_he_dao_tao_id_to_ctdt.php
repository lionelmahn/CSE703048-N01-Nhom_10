<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            $table->unsignedBigInteger('he_dao_tao_id')->nullable()->after('loai_hinh_dao_tao_id');
            $table->foreign('he_dao_tao_id')->references('id')->on('he_dao_tao')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            $table->dropForeign(['he_dao_tao_id']);
            $table->dropColumn('he_dao_tao_id');
        });
    }
};
