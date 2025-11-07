<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoc_phan', function (Blueprint $table) {
            $table->id();
            $table->string('ma_hp')->unique();
            $table->string('ten_hp');
            $table->integer('so_tinchi');
            $table->unsignedBigInteger('khoa_id');
            $table->unsignedBigInteger('bo_mon_id')->nullable();
            $table->text('mo_ta')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->foreign('khoa_id')->references('id')->on('khoa')->cascadeOnDelete();
            $table->foreign('bo_mon_id')->references('id')->on('bo_mon')->nullOnDelete();
            $table->index('ma_hp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoc_phan');
    }
};
