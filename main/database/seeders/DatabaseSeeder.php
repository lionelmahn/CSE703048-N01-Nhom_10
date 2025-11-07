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

        $khoa2 = Khoa::firstOrCreate(
            ['ma' => 'DT'],
            ['ten' => 'Khoa Điều Tiết', 'mo_ta' => 'Khoa Điều Tiết']
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

        // Create BoMons
        BoMon::firstOrCreate(['ma' => 'HTDL', 'khoa_id' => $khoa1->id], ['ten' => 'Bộ môn Hệ thống Dữ liệu']);
        BoMon::firstOrCreate(['ma' => 'LPHT', 'khoa_id' => $khoa1->id], ['ten' => 'Bộ môn Lập Trình Hướng Tương Tác']);
        BoMon::firstOrCreate(['ma' => 'TTPT', 'khoa_id' => $khoa1->id], ['ten' => 'Bộ môn Thiết Thực Phần Mềm']);

        // Create HeDaoTao
        $heDaoTao = HeDaoTao::firstOrCreate(
            ['ma' => 'DH'],
            ['ten' => 'Đại Học']
        );

        // Create Nganhs
        $nganh = Nganh::firstOrCreate(
            ['ma' => 'KHMT', 'he_dao_tao_id' => $heDaoTao->id],
            ['ten' => 'Khoa học máy tính']
        );

        // Create NienKhoas
        $nienKhoa = NienKhoa::firstOrCreate(
            ['ma' => '2024-2025'],
            ['nam_bat_dau' => 2024, 'nam_ket_thuc' => 2025]
        );

        // Create KhoiKienThucs
        $khoiDaiCuong = KhoiKienThuc::firstOrCreate(
            ['ma' => 'DAI_CUONG'],
            ['ten' => 'Đại Cương']
        );

        $khoiChuyenNganh = KhoiKienThuc::firstOrCreate(
            ['ma' => 'CHUYEN_NGANH'],
            ['ten' => 'Chuyên Ngành']
        );

        // Create HocPhans
        $hocPhans = [
            ['ma_hp' => 'MAT101', 'ten_hp' => 'Toán Cao Cấp 1', 'so_tinchi' => 3, 'khoa_id' => $khoa1->id],
            ['ma_hp' => 'MAT102', 'ten_hp' => 'Toán Cao Cấp 2', 'so_tinchi' => 3, 'khoa_id' => $khoa1->id],
            ['ma_hp' => 'PHY101', 'ten_hp' => 'Vật Lý 1', 'so_tinchi' => 3, 'khoa_id' => $khoa1->id],
            ['ma_hp' => 'PRG101', 'ten_hp' => 'Lập Trình Cơ Bản', 'so_tinchi' => 4, 'khoa_id' => $khoa1->id],
            ['ma_hp' => 'PRG102', 'ten_hp' => 'Cấu Trúc Dữ Liệu', 'so_tinchi' => 4, 'khoa_id' => $khoa1->id],
            ['ma_hp' => 'PRG201', 'ten_hp' => 'Lập Trình Hướng Đối Tượng', 'so_tinchi' => 4, 'khoa_id' => $khoa1->id],
            ['ma_hp' => 'DB101', 'ten_hp' => 'Cơ Sở Dữ Liệu', 'so_tinchi' => 3, 'khoa_id' => $khoa1->id],
            ['ma_hp' => 'WEB101', 'ten_hp' => 'Phát Triển Web', 'so_tinchi' => 3, 'khoa_id' => $khoa1->id],
        ];

        foreach ($hocPhans as $hocPhanData) {
            HocPhan::firstOrCreate(['ma_hp' => $hocPhanData['ma_hp']], $hocPhanData);
        }

        // Create Demo CTDT
        $ctdt1 = ChuongTrinhDaoTao::firstOrCreate(
            ['ma_ctdt' => 'CTDT-CNTT-2024'],
            [
                'ten' => 'Chương Trình Đào Tạo Khoa Học Máy Tính 2024',
                'khoa_id' => $khoa1->id,
                'nganh_id' => $nganh->id,
                'he_dao_tao_id' => $heDaoTao->id,
                'nien_khoa_id' => $nienKhoa->id,
                'trang_thai' => 'draft',
                'hieu_luc_tu' => now(),
                'hieu_luc_den' => now()->addYears(4),
                'created_by' => $admin->id,
            ]
        );

        $ctdt2 = ChuongTrinhDaoTao::firstOrCreate(
            ['ma_ctdt' => 'CTDT-CNTT-2023'],
            [
                'ten' => 'Chương Trình Đào Tạo Khoa Học Máy Tính 2023',
                'khoa_id' => $khoa1->id,
                'nganh_id' => $nganh->id,
                'he_dao_tao_id' => $heDaoTao->id,
                'nien_khoa_id' => $nienKhoa->id,
                'trang_thai' => 'published',
                'hieu_luc_tu' => now()->subYears(1),
                'hieu_luc_den' => now()->addYears(3),
                'created_by' => $admin->id,
            ]
        );
    }
}
