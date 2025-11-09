<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NienKhoa;

class NienKhoaSeeder extends Seeder
{
    public function run(): void
    {
        $nienKhoas = [
            ['ma' => 'NK2021', 'nam_bat_dau' => 2021, 'nam_ket_thuc' => 2022],
            ['ma' => 'NK2022', 'nam_bat_dau' => 2022, 'nam_ket_thuc' => 2023],
            ['ma' => 'NK2023', 'nam_bat_dau' => 2023, 'nam_ket_thuc' => 2024],
            ['ma' => 'NK2024', 'nam_bat_dau' => 2024, 'nam_ket_thuc' => 2025],
            ['ma' => 'NK2025', 'nam_bat_dau' => 2025, 'nam_ket_thuc' => 2026],
        ];

        foreach ($nienKhoas as $nienKhoa) {
            NienKhoa::create($nienKhoa);
        }
    }
}
