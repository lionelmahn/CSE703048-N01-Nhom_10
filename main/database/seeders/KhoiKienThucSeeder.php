<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KhoiKienThuc;

class KhoiKienThucSeeder extends Seeder
{
    public function run(): void
    {
        $khois = [
            ['ma' => 'KHĐC', 'ten' => 'Kiến thức giáo dục đại cương'],
            ['ma' => 'KHCN', 'ten' => 'Kiến thức giáo dục chuyên nghiệp'],
            ['ma' => 'KCBB', 'ten' => 'Kiến thức cơ sở của ngành'],
            ['ma' => 'KCN', 'ten' => 'Kiến thức chuyên ngành'],
            ['ma' => 'BTR', 'ten' => 'Kiến thức bổ trợ'],
            ['ma' => 'TNTN', 'ten' => 'Thực tập tốt nghiệp'],
        ];

        foreach ($khois as $khoi) {
            KhoiKienThuc::create($khoi);
        }
    }
}
