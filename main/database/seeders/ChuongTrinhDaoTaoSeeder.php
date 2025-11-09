<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChuongTrinhDaoTao;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\ChuyenNganh;
use App\Models\BacHoc;
use App\Models\LoaiHinhDaoTao;
use App\Models\HeDaoTao;
use App\Models\NienKhoa;
use App\Models\User;

class ChuongTrinhDaoTaoSeeder extends Seeder
{
    public function run(): void
    {
        $cntt = Nganh::where('ma', '7480201')->first();
        $khmt = Nganh::where('ma', '7480103')->first();
        $cnpm = ChuyenNganh::where('ma', 'CNPM')->first();
        $khoaCNTT = Khoa::where('ma', 'CNTT')->first();
        $dh = BacHoc::where('ma', 'DH')->first();
        $chqd = LoaiHinhDaoTao::where('ma', 'CHQD')->first();
        $dhcq = HeDaoTao::where('ma', 'DHCQ')->first();
        $nienKhoa2023 = NienKhoa::where('ma', 'NK2023')->first();
        $admin = User::where('role', 'admin')->first();

        $ctdts = [
            [
                'ma_ctdt' => 'DH-CHQD-7480201-CNPM-2023',
                'ten' => 'Chương Trình Đào Tạo Công Nghệ Thông Tin 2023',
                'khoa_id' => $khoaCNTT->id,
                'nganh_id' => $cntt->id,
                'chuyen_nganh_id' => $cnpm->id,
                'bac_hoc_id' => $dh->id,
                'loai_hinh_dao_tao_id' => $chqd->id,
                'he_dao_tao_id' => $dhcq->id,
                'nien_khoa_id' => $nienKhoa2023->id,
                'trang_thai' => 'published',
                'hieu_luc_tu' => '2023-09-01',
                'hieu_luc_den' => '2027-08-31',
                'mo_ta' => 'Chương trình đào tạo ngành Công Nghệ Thông Tin theo chuẩn quốc tế, chuyên ngành Công nghệ Phần mềm',
                'created_by' => $admin->id,
            ],
            [
                'ma_ctdt' => 'DH-CHQD-7480103-2023',
                'ten' => 'Chương Trình Đào Tạo Khoa Học Máy Tính 2023',
                'khoa_id' => $khoaCNTT->id,
                'nganh_id' => $khmt->id,
                'chuyen_nganh_id' => null,
                'bac_hoc_id' => $dh->id,
                'loai_hinh_dao_tao_id' => $chqd->id,
                'he_dao_tao_id' => $dhcq->id,
                'nien_khoa_id' => $nienKhoa2023->id,
                'trang_thai' => 'approved',
                'hieu_luc_tu' => '2023-09-01',
                'hieu_luc_den' => null,
                'mo_ta' => 'Chương trình đào tạo ngành Khoa Học Máy Tính',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($ctdts as $ctdt) {
            ChuongTrinhDaoTao::create($ctdt);
        }
    }
}
