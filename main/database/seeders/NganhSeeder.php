<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nganh;

class NganhSeeder extends Seeder
{
    public function run(): void
    {
        $nganhs = [
            ['ma' => '7480201', 'ten' => 'Công Nghệ Thông Tin', 'active' => true],
            ['ma' => '7480103', 'ten' => 'Khoa Học Máy Tính', 'active' => true],
            ['ma' => '7480209', 'ten' => 'An Toàn Thông Tin', 'active' => true],
            ['ma' => '7520114', 'ten' => 'Kỹ Thuật Điện Tử Truyền Thông', 'active' => true],
            ['ma' => '7340101', 'ten' => 'Quản Trị Kinh Doanh', 'active' => true],
            ['ma' => '7480104', 'ten' => 'Hệ Thống Thông Tin', 'active' => true],
            ['ma' => '7520212', 'ten' => 'Kỹ Thuật Phần Mềm', 'active' => true],
            ['ma' => '7340120', 'ten' => 'Marketing', 'active' => false], // Inactive example
        ];

        foreach ($nganhs as $nganh) {
            Nganh::create($nganh);
        }
    }
}
