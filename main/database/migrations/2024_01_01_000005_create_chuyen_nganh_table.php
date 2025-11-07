<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chuyen_nganh', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->string('ten');
            $table->unsignedBigInteger('nganh_id');
            $table->timestamps();
            $table->foreign('nganh_id')->references('id')->on('nganh')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chuyen_nganh');
    }
};
