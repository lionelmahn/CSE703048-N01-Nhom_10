<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChuongTrinhDaoTao;
use App\Models\CtdtHocPhan;
use App\Models\CtdtKhoi;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\ChuyenNganh;
use App\Models\BacHoc;
use App\Models\LoaiHinhDaoTao;
use App\Models\HeDaoTao;
use App\Models\NienKhoa;
use App\Models\KhoaHoc;
use App\Models\KhoiKienThuc;
use App\Models\HocPhan;
use App\Models\User;

class ChuongTrinhDaoTaoSeeder extends Seeder
{
    public function run(): void
    {
        // Get reference data
        $cntt = Nganh::where('ma', '7480201')->first();
        $khmt = Nganh::where('ma', '7480103')->first();
        $attt = Nganh::where('ma', '7480209')->first();

        $cnpm = ChuyenNganh::where('ma', 'CNPM')->first();
        $khoaCNTT = Khoa::where('ma', 'CNTT')->first();

        $dh = BacHoc::where('ma', 'DH')->first();
        $chqd = LoaiHinhDaoTao::where('ma', 'CHQD')->first();
        $clcn = LoaiHinhDaoTao::where('ma', 'CLCN')->first();
        $dhcq = HeDaoTao::where('ma', 'DHCQ')->first();

        $nienKhoa2023 = NienKhoa::where('ma', 'NK2023')->first();
        $nienKhoa2024 = NienKhoa::where('ma', 'NK2024')->first();

        $k46 = KhoaHoc::where('ma', 'K46')->first();
        $k47 = KhoaHoc::where('ma', 'K47')->first();

        $admin = User::where('role', 'admin')->first();

        $ctdts = [
            [
                'ma_ctdt' => 'DH-CHQD-7480201-CNPM-K46',
                'ten' => 'Chương trình Đào tạo Công Nghệ Thông Tin 2023',
                'khoa_id' => $khoaCNTT->id,
                'nganh_id' => $cntt->id,
                'chuyen_nganh_id' => $cnpm->id,
                'bac_hoc_id' => $dh->id,
                'loai_hinh_dao_tao_id' => $chqd->id,
                'he_dao_tao_id' => $dhcq->id,
                'nien_khoa_id' => $nienKhoa2023->id,
                'khoa_hoc_id' => $k46->id,
                'trang_thai' => 'da_phe_duyet',
                'hieu_luc_tu' => '2023-09-01',
                'hieu_luc_den' => '2027-08-31',
                'mo_ta' => 'Chương trình đào tạo ngành Công Nghệ Thông Tin theo chuẩn quốc tế, chuyên ngành Công nghệ Phần mềm',
                'created_by' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(10),
            ],
            [
                'ma_ctdt' => 'DH-CHQD-7480103-K46',
                'ten' => 'Chương trình Đào tạo Khoa Học Máy Tính 2023',
                'khoa_id' => $khoaCNTT->id,
                'nganh_id' => $khmt->id,
                'chuyen_nganh_id' => null,
                'bac_hoc_id' => $dh->id,
                'loai_hinh_dao_tao_id' => $chqd->id,
                'he_dao_tao_id' => $dhcq->id,
                'nien_khoa_id' => $nienKhoa2023->id,
                'khoa_hoc_id' => $k46->id,
                'trang_thai' => 'published',
                'hieu_luc_tu' => '2023-09-01',
                'hieu_luc_den' => null,
                'mo_ta' => 'Chương trình đào tạo ngành Khoa Học Máy Tính',
                'created_by' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(5),
            ],
            [
                'ma_ctdt' => 'DH-CLCN-7480209-K47',
                'ten' => 'Chương trình Đào tạo An Toàn Thông Tin CLC 2024',
                'khoa_id' => $khoaCNTT->id,
                'nganh_id' => $attt->id,
                'chuyen_nganh_id' => null,
                'bac_hoc_id' => $dh->id,
                'loai_hinh_dao_tao_id' => $clcn->id,
                'he_dao_tao_id' => $dhcq->id,
                'nien_khoa_id' => $nienKhoa2024->id,
                'khoa_hoc_id' => $k47->id,
                'trang_thai' => 'cho_phe_duyet',
                'hieu_luc_tu' => '2024-09-01',
                'hieu_luc_den' => null,
                'mo_ta' => 'Chương trình đào tạo chất lượng cao ngành An Toàn Thông Tin',
                'created_by' => $admin->id,
            ],
            [
                'ma_ctdt' => 'DH-CHQD-7480201-K47',
                'ten' => 'Chương trình Đào tạo CNTT 2024 (Draft)',
                'khoa_id' => $khoaCNTT->id,
                'nganh_id' => $cntt->id,
                'chuyen_nganh_id' => null,
                'bac_hoc_id' => $dh->id,
                'loai_hinh_dao_tao_id' => $chqd->id,
                'he_dao_tao_id' => $dhcq->id,
                'nien_khoa_id' => $nienKhoa2024->id,
                'khoa_hoc_id' => $k47->id,
                'trang_thai' => 'draft',
                'hieu_luc_tu' => null,
                'hieu_luc_den' => null,
                'mo_ta' => 'Chương trình đang soạn thảo',
                'created_by' => $admin->id,
            ],
            [
                'ma_ctdt' => 'DH-CHQD-7480103-K47',
                'ten' => 'Chương trình KHMT 2024 (Cần chỉnh sửa)',
                'khoa_id' => $khoaCNTT->id,
                'nganh_id' => $khmt->id,
                'chuyen_nganh_id' => null,
                'bac_hoc_id' => $dh->id,
                'loai_hinh_dao_tao_id' => $chqd->id,
                'he_dao_tao_id' => $dhcq->id,
                'nien_khoa_id' => $nienKhoa2024->id,
                'khoa_hoc_id' => $k47->id,
                'trang_thai' => 'can_chinh_sua',
                'hieu_luc_tu' => null,
                'hieu_luc_den' => null,
                'mo_ta' => 'Chương trình bị trả về để chỉnh sửa',
                'ly_do_tra_ve' => 'Cần bổ sung thêm học phần thực tập và điều chỉnh số tín chỉ',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($ctdts as $ctdtData) {
            $ctdt = ChuongTrinhDaoTao::create($ctdtData);

            // Add sample course structure for first CTDT
            if ($ctdt->ma_ctdt === 'DH-CHQD-7480201-CNPM-K46') {
                $this->addCourseStructure($ctdt);
            }
        }
    }

    private function addCourseStructure($ctdt)
    {
        $daiCuong = KhoiKienThuc::where('ma', 'KHĐC')->first();
        $coSo = KhoiKienThuc::where('ma', 'KCBB')->first();
        $chuyenNganh = KhoiKienThuc::where('ma', 'KCN')->first();

        if (!$daiCuong || !$coSo || !$chuyenNganh) {
            $this->command->warn('⚠️  Warning: Some KhoiKienThuc not found. Skipping course structure.');
            return;
        }

        // Create CTDT Khoi - using correct field names: khoi_id, thu_tu, ghi_chu
        $khoiDC = CtdtKhoi::create([
            'ctdt_id' => $ctdt->id,
            'khoi_id' => $daiCuong->id,
            'thu_tu' => 1,
            'ghi_chu' => 'Khối kiến thức đại cương: 24 TC bắt buộc, 6 TC tự chọn',
        ]);

        $khoiCS = CtdtKhoi::create([
            'ctdt_id' => $ctdt->id,
            'khoi_id' => $coSo->id,
            'thu_tu' => 2,
            'ghi_chu' => 'Khối kiến thức cơ sở ngành: 36 TC bắt buộc, 9 TC tự chọn',
        ]);

        $khoiCN = CtdtKhoi::create([
            'ctdt_id' => $ctdt->id,
            'khoi_id' => $chuyenNganh->id,
            'thu_tu' => 3,
            'ghi_chu' => 'Khối kiến thức chuyên ngành: 30 TC bắt buộc, 15 TC tự chọn',
        ]);

        // Add courses to blocks
        $hocPhans = HocPhan::where('active', true)->limit(10)->get();

        if ($hocPhans->count() < 10) {
            $this->command->warn('⚠️  Warning: Not enough courses found. Skipping course assignment.');
            return;
        }

        // Add to Đại cương block - using correct field names: khoi_id, hoc_ky, loai, thu_tu
        foreach ($hocPhans->slice(0, 4) as $index => $hp) {
            CtdtHocPhan::create([
                'ctdt_id' => $ctdt->id,
                'hoc_phan_id' => $hp->id,
                'khoi_id' => $daiCuong->id,
                'loai' => 'bat_buoc',
                'hoc_ky' => $index + 1,
                'thu_tu' => $index + 1,
            ]);
        }

        // Add to Cơ sở block
        foreach ($hocPhans->slice(4, 3) as $index => $hp) {
            CtdtHocPhan::create([
                'ctdt_id' => $ctdt->id,
                'hoc_phan_id' => $hp->id,
                'khoi_id' => $coSo->id,
                'loai' => 'bat_buoc',
                'hoc_ky' => $index + 3,
                'thu_tu' => $index + 1,
            ]);
        }

        // Add to Chuyên ngành block (mix of required and elective)
        foreach ($hocPhans->slice(7, 3) as $index => $hp) {
            CtdtHocPhan::create([
                'ctdt_id' => $ctdt->id,
                'hoc_phan_id' => $hp->id,
                'khoi_id' => $chuyenNganh->id,
                'loai' => $index < 2 ? 'bat_buoc' : 'tu_chon',
                'hoc_ky' => $index + 5,
                'thu_tu' => $index + 1,
            ]);
        }
    }
}
