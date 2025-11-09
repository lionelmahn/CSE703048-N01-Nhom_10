<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Khoa;

class KhoaSeeder extends Seeder
{
    public function run(): void
    {
        $khoas = [
            ['ma' => 'CNTT', 'ten' => 'Công Nghệ Thông Tin'],
            ['ma' => 'KTDTVT', 'ten' => 'Kỹ Thuật Điện Tử Viễn Thông'],
            ['ma' => 'KT', 'ten' => 'Kinh Tế'],
            ['ma' => 'KHTN', 'ten' => 'Khoa Học Tự Nhiên'],
        ];

        foreach ($khoas as $khoa) {
            Khoa::create($khoa);
        }
    }
}
