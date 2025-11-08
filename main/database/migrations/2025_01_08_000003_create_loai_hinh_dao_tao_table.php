<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loai_hinh_dao_tao', function (Blueprint $table) {
            $table->id();
            $table->string('ma', 10)->unique(); // VD: CQ, VHVL
            $table->string('ten'); // VD: Chính quy, Vừa học vừa làm
            $table->timestamps();
        });
        
        // Seed some default data
        DB::table('loai_hinh_dao_tao')->insert([
            ['ma' => 'CQ', 'ten' => 'Chính quy', 'created_at' => now(), 'updated_at' => now()],
            ['ma' => 'VHVL', 'ten' => 'Vừa học vừa làm', 'created_at' => now(), 'updated_at' => now()],
            ['ma' => 'LT', 'ten' => 'Liên thông', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_hinh_dao_tao');
    }
};
