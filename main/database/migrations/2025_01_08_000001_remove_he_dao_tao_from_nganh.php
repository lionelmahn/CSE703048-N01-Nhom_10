<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nganh', function (Blueprint $table) {
            $table->dropForeign(['he_dao_tao_id']);
            $table->dropColumn('he_dao_tao_id');
        });
    }

    public function down(): void
    {
        Schema::table('nganh', function (Blueprint $table) {
            $table->unsignedBigInteger('he_dao_tao_id')->after('ten');
            $table->foreign('he_dao_tao_id')->references('id')->on('he_dao_tao')->cascadeOnDelete();
        });
    }
};
