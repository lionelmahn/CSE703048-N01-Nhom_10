<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctdt_khoi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ctdt_id');
            $table->unsignedBigInteger('khoi_id');
            $table->integer('thu_tu')->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            
            $table->foreign('ctdt_id')->references('id')->on('chuong_trinh_dao_tao')->cascadeOnDelete();
            $table->foreign('khoi_id')->references('id')->on('khoi_kien_thuc')->cascadeOnDelete();
            $table->unique(['ctdt_id', 'khoi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ctdt_khoi');
    }
};
