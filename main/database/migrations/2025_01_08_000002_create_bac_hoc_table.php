<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bac_hoc', function (Blueprint $table) {
            $table->id();
            $table->string('ma', 10)->unique(); // VD: DH, CD, TC
            $table->string('ten'); // VD: Đại học, Cao đẳng, Trung cấp
            $table->timestamps();
        });
        
        // Seed some default data
        DB::table('bac_hoc')->insert([
            ['ma' => 'DH', 'ten' => 'Đại học', 'created_at' => now(), 'updated_at' => now()],
            ['ma' => 'CD', 'ten' => 'Cao đẳng', 'created_at' => now(), 'updated_at' => now()],
            ['ma' => 'TC', 'ten' => 'Trung cấp', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bac_hoc');
    }
};
