<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chuong_trinh_dao_tao', function (Blueprint $table) {
            $table->id();
            $table->string('ma_ctdt')->unique();
            $table->string('ten');
            $table->unsignedBigInteger('khoa_id');
            $table->unsignedBigInteger('nganh_id');
            $table->unsignedBigInteger('chuyen_nganh_id')->nullable();
            $table->unsignedBigInteger('he_dao_tao_id');
            $table->unsignedBigInteger('nien_khoa_id');
            $table->enum('trang_thai', ['draft', 'pending', 'approved', 'published', 'archived'])->default('draft');
            $table->date('hieu_luc_tu')->nullable();
            $table->date('hieu_luc_den')->nullable();
            $table->text('mo_ta')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->text('ly_do_tra_ve')->nullable();
            $table->timestamps();
            
            $table->foreign('khoa_id')->references('id')->on('khoa')->cascadeOnDelete();
            $table->foreign('nganh_id')->references('id')->on('nganh')->cascadeOnDelete();
            $table->foreign('chuyen_nganh_id')->references('id')->on('chuyen_nganh')->nullOnDelete();
            $table->foreign('he_dao_tao_id')->references('id')->on('he_dao_tao')->cascadeOnDelete();
            $table->foreign('nien_khoa_id')->references('id')->on('nien_khoa')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            
            $table->index('ma_ctdt');
            $table->index(['khoa_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chuong_trinh_dao_tao');
    }
};
