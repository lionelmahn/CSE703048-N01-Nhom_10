<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            $table->unsignedBigInteger('bac_hoc_id')->after('ten')->nullable();
            $table->unsignedBigInteger('loai_hinh_dao_tao_id')->after('bac_hoc_id')->nullable();
            $table->unsignedBigInteger('khoa_hoc_id')->after('nien_khoa_id')->nullable();
            
            $table->foreign('bac_hoc_id')->references('id')->on('bac_hoc')->nullOnDelete();
            $table->foreign('loai_hinh_dao_tao_id')->references('id')->on('loai_hinh_dao_tao')->nullOnDelete();
            $table->foreign('khoa_hoc_id')->references('id')->on('khoa_hoc')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            $table->dropForeign(['bac_hoc_id']);
            $table->dropForeign(['loai_hinh_dao_tao_id']);
            $table->dropForeign(['khoa_hoc_id']);
            $table->dropColumn(['bac_hoc_id', 'loai_hinh_dao_tao_id', 'khoa_hoc_id']);
        });
    }
};
