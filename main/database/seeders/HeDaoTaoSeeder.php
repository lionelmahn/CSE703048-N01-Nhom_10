<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeDaoTao;

class HeDaoTaoSeeder extends Seeder
{
    public function run(): void
    {
        $heDaoTaos = [
            ['ma' => 'DHCQ', 'ten' => 'Đại học chính quy'],
            ['ma' => 'DHVB2', 'ten' => 'Đại học văn bằng 2'],
            ['ma' => 'LTDH', 'ten' => 'Liên thông đại học'],
            ['ma' => 'SDP', 'ten' => 'Sư phạm'],
        ];

        foreach ($heDaoTaos as $heDaoTao) {
            HeDaoTao::create($heDaoTao);
        }
    }
}
