<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->unsignedBigInteger('nien_khoa_id');
            $table->timestamps();
            $table->foreign('nien_khoa_id')->references('id')->on('nien_khoa')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khoa_hoc');
    }
};
