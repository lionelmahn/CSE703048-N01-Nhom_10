<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nganh;

class NganhSeeder extends Seeder
{
    public function run(): void
    {
        $nganhs = [
            ['ma' => '7480201', 'ten' => 'Công Nghệ Thông Tin'],
            ['ma' => '7480103', 'ten' => 'Khoa Học Máy Tính'],
            ['ma' => '7480209', 'ten' => 'An Toàn Thông Tin'],
            ['ma' => '7520114', 'ten' => 'Kỹ Thuật Điện Tử Truyền Thông'],
            ['ma' => '7340101', 'ten' => 'Quản Trị Kinh Doanh'],
        ];

        foreach ($nganhs as $nganh) {
            Nganh::create($nganh);
        }
    }
}
