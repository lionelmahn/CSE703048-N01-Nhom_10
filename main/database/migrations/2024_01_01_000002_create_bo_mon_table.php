<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bo_mon', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->string('ten');
            $table->unsignedBigInteger('khoa_id');
            $table->timestamps();
            $table->foreign('khoa_id')->references('id')->on('khoa')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bo_mon');
    }
};
