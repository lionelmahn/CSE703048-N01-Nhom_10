<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChuyenNganh;
use App\Models\Nganh;

class ChuyenNganhSeeder extends Seeder
{
    public function run(): void
    {
        $cntt = Nganh::where('ma', '7480201')->first();
        $khmt = Nganh::where('ma', '7480103')->first();

        $chuyenNganhs = [
            ['ma' => 'CNPM', 'ten' => 'Công Nghệ Phần Mềm', 'nganh_id' => $cntt->id],
            ['ma' => 'HTTT', 'ten' => 'Hệ Thống Thông Tin', 'nganh_id' => $cntt->id],
            ['ma' => 'MMT', 'ten' => 'Mạng Máy Tính và Truyền Thông', 'nganh_id' => $cntt->id],
            ['ma' => 'AI', 'ten' => 'Trí Tuệ Nhân Tạo', 'nganh_id' => $khmt->id],
            ['ma' => 'DS', 'ten' => 'Khoa Học Dữ Liệu', 'nganh_id' => $khmt->id],
        ];

        foreach ($chuyenNganhs as $chuyenNganh) {
            ChuyenNganh::create($chuyenNganh);
        }
    }
}
