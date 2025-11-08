<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Khoa;
use App\Models\BoMon;
use App\Models\HocPhan;
use App\Models\HeDaoTao;
use App\Models\Nganh;
use App\Models\NienKhoa;
use App\Models\KhoiKienThuc;
use App\Models\ChuongTrinhDaoTao;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Create Demo Users with roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.test'],
            ['name' => 'Admin Demo', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        // Create Khoas
        $khoa1 = Khoa::firstOrCreate(
            ['ma' => 'CNTT'],
            ['ten' => 'Khoa Công Nghệ Thông Tin', 'mo_ta' => 'Khoa Công Nghệ Thông Tin']
        );

        // Create Khoa Users
        $khoaUser = User::firstOrCreate(
            ['email' => 'khoa@demo.test'],
            ['name' => 'Khoa Demo', 'password' => bcrypt('password'), 'khoa_id' => $khoa1->id, 'role' => 'khoa']
        );

        $gvUser = User::firstOrCreate(
            ['email' => 'gv@demo.test'],
            ['name' => 'Giảng Viên Demo', 'password' => bcrypt('password'), 'khoa_id' => $khoa1->id, 'role' => 'giang_vien']
        );

        $svUser = User::firstOrCreate(
            ['email' => 'sv@demo.test'],
            ['name' => 'Sinh Viên Demo', 'password' => bcrypt('password'), 'khoa_id' => $khoa1->id, 'role' => 'sinh_vien']
        );
    }
}
