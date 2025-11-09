<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoMon;
use App\Models\Khoa;

class BoMonSeeder extends Seeder
{
    public function run(): void
    {
        $cntt = Khoa::where('ma', 'CNTT')->first();
        $ktdtvt = Khoa::where('ma', 'KTDTVT')->first();
        $kt = Khoa::where('ma', 'KT')->first();

        $boMons = [
            ['ma' => 'KHMT', 'ten' => 'Khoa Học Máy Tính', 'khoa_id' => $cntt->id],
            ['ma' => 'CNPM', 'ten' => 'Công Nghệ Phần Mềm', 'khoa_id' => $cntt->id],
            ['ma' => 'MMT', 'ten' => 'Mạng Máy Tính', 'khoa_id' => $cntt->id],
            ['ma' => 'KTDT', 'ten' => 'Kỹ Thuật Điện Tử', 'khoa_id' => $ktdtvt->id],
            ['ma' => 'QTKD', 'ten' => 'Quản Trị Kinh Doanh', 'khoa_id' => $kt->id],
        ];

        foreach ($boMons as $boMon) {
            BoMon::create($boMon);
        }
    }
}
