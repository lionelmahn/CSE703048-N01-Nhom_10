<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khoi_kien_thuc', function (Blueprint $table) {
            $table->id();
            $table->string('ma')->unique();
            $table->string('ten');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khoi_kien_thuc');
    }
};
