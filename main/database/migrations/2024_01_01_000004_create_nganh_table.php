<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nganh', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->string('ten');
            $table->unsignedBigInteger('he_dao_tao_id');
            $table->timestamps();
            $table->foreign('he_dao_tao_id')->references('id')->on('he_dao_tao')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nganh');
    }
};
