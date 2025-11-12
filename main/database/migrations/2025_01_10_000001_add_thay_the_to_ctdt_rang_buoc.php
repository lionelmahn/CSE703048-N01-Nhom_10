<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE ctdt_rang_buoc MODIFY COLUMN kieu ENUM('tien_quyet', 'song_hanh', 'thay_the') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ctdt_rang_buoc MODIFY COLUMN kieu ENUM('tien_quyet', 'song_hanh') NOT NULL");
    }
};
