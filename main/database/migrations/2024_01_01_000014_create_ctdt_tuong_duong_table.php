<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctdt_tuong_duong', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ctdt_id');
            $table->unsignedBigInteger('hoc_phan_id');
            $table->unsignedBigInteger('tuong_duong_hp_id');
            $table->enum('pham_vi', ['toan_phan', 'mot_phan'])->default('toan_phan');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            
            $table->foreign('ctdt_id')->references('id')->on('chuong_trinh_dao_tao')->cascadeOnDelete();
            $table->foreign('hoc_phan_id')->references('id')->on('hoc_phan')->cascadeOnDelete();
            $table->foreign('tuong_duong_hp_id')->references('id')->on('hoc_phan')->cascadeOnDelete();
            
            $table->index('ctdt_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ctdt_tuong_duong');
    }
};
