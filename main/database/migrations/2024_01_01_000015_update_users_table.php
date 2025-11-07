<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'khoa_id')) {
                $table->unsignedBigInteger('khoa_id')->nullable()->after('id');
                $table->foreign('khoa_id')->references('id')->on('khoa')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['khoa_id']);
            $table->dropColumn('khoa_id');
        });
    }
};
