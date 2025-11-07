<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khoa', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->string('ten');
            $table->text('mo_ta')->nullable();
            $table->unsignedBigInteger('nguoi_phu_trach')->nullable();
            $table->timestamps();
            $table->foreign('nguoi_phu_trach')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khoa');
    }
};
