<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KhoaHoc;
use App\Models\NienKhoa;

class KhoaHocSeeder extends Seeder
{
    public function run(): void
    {
        $nk2021 = NienKhoa::where('nam_bat_dau', 2021)->first();
        $nk2022 = NienKhoa::where('nam_bat_dau', 2022)->first();
        $nk2023 = NienKhoa::where('nam_bat_dau', 2023)->first();

        $khoaHocs = [
            ['ma' => 'K45', 'nien_khoa_id' => $nk2021->id],
            ['ma' => 'K46', 'nien_khoa_id' => $nk2022->id],
            ['ma' => 'K47', 'nien_khoa_id' => $nk2023->id],
        ];

        foreach ($khoaHocs as $khoaHoc) {
            KhoaHoc::create($khoaHoc);
        }
    }
}
