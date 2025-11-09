<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HocPhan;
use App\Models\Khoa;

class HocPhanSeeder extends Seeder
{
    public function run(): void
    {
        $cntt = Khoa::where('ma', 'CNTT')->first();
        $khtn = Khoa::where('ma', 'KHTN')->first();

        $hocPhans = [
            // Khối đại cương
            ['ma_hp' => 'HP1', 'ten_hp' => 'Tiếng Anh 1', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP2', 'ten_hp' => 'Toán Cao Cấp A1', 'so_tinchi' => 3, 'khoa_id' => $khtn->id, 'active' => true],
            ['ma_hp' => 'HP3', 'ten_hp' => 'Vật Lý Đại Cương', 'so_tinchi' => 3, 'khoa_id' => $khtn->id, 'active' => true],
            ['ma_hp' => 'HP4', 'ten_hp' => 'Triết Học Mác-Lênin', 'so_tinchi' => 3, 'khoa_id' => $khtn->id, 'active' => true],

            // Khối cơ sở ngành
            ['ma_hp' => 'HP5', 'ten_hp' => 'Cấu Trúc Dữ Liệu và Giải Thuật', 'so_tinchi' => 4, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP6', 'ten_hp' => 'Lập Trình Hướng Đối Tượng', 'so_tinchi' => 4, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP7', 'ten_hp' => 'Cơ Sở Dữ Liệu', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP8', 'ten_hp' => 'Hệ Điều Hành', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => true],

            // Khối chuyên ngành
            ['ma_hp' => 'HP9', 'ten_hp' => 'Công Nghệ Phần Mềm', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP10', 'ten_hp' => 'Phát Triển Ứng Dụng Web', 'so_tinchi' => 4, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP11', 'ten_hp' => 'Phát Triển Ứng Dụng Di Động', 'so_tinchi' => 4, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP12', 'ten_hp' => 'Trí Tuệ Nhân Tạo', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP13', 'ten_hp' => 'Học Máy', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => true],
            ['ma_hp' => 'HP14', 'ten_hp' => 'An Toàn Và Bảo Mật Thông Tin', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => true],

            // Học phần inactive để test
            ['ma_hp' => 'HP15', 'ten_hp' => 'Học Phần Ngừng Hoạt Động', 'so_tinchi' => 3, 'khoa_id' => $cntt->id, 'active' => false],
        ];

        foreach ($hocPhans as $hocPhan) {
            HocPhan::create($hocPhan);
        }
    }
}
