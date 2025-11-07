<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctdt_hoc_phan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ctdt_id');
            $table->unsignedBigInteger('hoc_phan_id');
            $table->unsignedBigInteger('khoi_id')->nullable();
            $table->integer('hoc_ky')->nullable();
            $table->enum('loai', ['bat_buoc', 'tu_chon'])->default('bat_buoc');
            $table->integer('thu_tu')->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            
            $table->foreign('ctdt_id')->references('id')->on('chuong_trinh_dao_tao')->cascadeOnDelete();
            $table->foreign('hoc_phan_id')->references('id')->on('hoc_phan')->cascadeOnDelete();
            $table->foreign('khoi_id')->references('id')->on('khoi_kien_thuc')->nullOnDelete();
            
            $table->unique(['ctdt_id', 'hoc_phan_id']);
            $table->index(['ctdt_id', 'khoi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ctdt_hoc_phan');
    }
};
