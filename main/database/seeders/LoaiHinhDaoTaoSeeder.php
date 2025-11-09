<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoaiHinhDaoTao;

class LoaiHinhDaoTaoSeeder extends Seeder
{
    public function run(): void
    {
        $loaiHinhs = [
            ['ma' => 'CHQD', 'ten' => 'Chính quy đại trà'],
            ['ma' => 'CLCN', 'ten' => 'Chất lượng cao ngành'],
            ['ma' => 'CLCK', 'ten' => 'Chất lượng cao khoa'],
            ['ma' => 'CQVB', 'ten' => 'Chính quy văn bằng 2'],
            ['ma' => 'LTDH', 'ten' => 'Liên thông đại học'],
        ];

        foreach ($loaiHinhs as $loaiHinh) {
            LoaiHinhDaoTao::create($loaiHinh);
        }
    }
}
