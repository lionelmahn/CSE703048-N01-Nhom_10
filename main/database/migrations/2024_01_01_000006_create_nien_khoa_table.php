<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nien_khoa', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->year('nam_bat_dau');
            $table->year('nam_ket_thuc');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nien_khoa');
    }
};
