<?php

namespace App\Services;

use App\Models\ChuongTrinhDaoTao;
use App\Models\CtdtHocPhan;
use App\Models\CtdtRangBuoc;
use App\Models\CtdtTuongDuong;
use App\Models\CtdtKhoi;
use Illuminate\Support\Facades\DB;
use Exception;

class CTDTService
{
    /**
     * Sao chép CTDT từ niên khóa trước
     */
    public function cloneFromPreviousYear(ChuongTrinhDaoTao $sourceCTDT, $newNienKhoaId): ChuongTrinhDaoTao
    {
        return DB::transaction(function () use ($sourceCTDT, $newNienKhoaId) {
            // Tạo CTDT mới
            $newCTDT = $sourceCTDT->replicate();
            $newCTDT->nien_khoa_id = $newNienKhoaId;
            $newCTDT->trang_thai = 'draft';
            $newCTDT->ma_ctdt = $this->generateUniqueMaCTDT($sourceCTDT->khoa_id, $newNienKhoaId);
            $newCTDT->save();

            // Copy khối
            foreach ($sourceCTDT->ctdtKhois as $khoi) {
                $newCTDT->ctdtKhois()->create([
                    'khoi_id' => $khoi->khoi_id,
                    'thu_tu' => $khoi->thu_tu,
                    'ghi_chu' => $khoi->ghi_chu,
                ]);
            }

            // Copy học phần
            foreach ($sourceCTDT->ctdtHocPhans as $hocPhan) {
                $newCTDT->ctdtHocPhans()->create([
                    'hoc_phan_id' => $hocPhan->hoc_phan_id,
                    'khoi_id' => $hocPhan->khoi_id,
                    'hoc_ky' => $hocPhan->hoc_ky,
                    'loai' => $hocPhan->loai,
                    'thu_tu' => $hocPhan->thu_tu,
                    'ghi_chu' => $hocPhan->ghi_chu,
                ]);
            }

            // Copy ràng buộc
            foreach ($sourceCTDT->ctdtRangBuocs as $rangBuoc) {
                $newCTDT->ctdtRangBuocs()->create([
                    'hoc_phan_id' => $rangBuoc->hoc_phan_id,
                    'lien_quan_hp_id' => $rangBuoc->lien_quan_hp_id,
                    'kieu' => $rangBuoc->kieu,
                    'logic_nhom' => $rangBuoc->logic_nhom,
                    'nhom' => $rangBuoc->nhom,
                    'ghi_chu' => $rangBuoc->ghi_chu,
                ]);
            }

            // Copy tương đương
            foreach ($sourceCTDT->ctdtTuongDuongs as $tuongDuong) {
                $newCTDT->ctdtTuongDuongs()->create([
                    'hoc_phan_id' => $tuongDuong->hoc_phan_id,
                    'tuong_duong_hp_id' => $tuongDuong->tuong_duong_hp_id,
                    'pham_vi' => $tuongDuong->pham_vi,
                    'ghi_chu' => $tuongDuong->ghi_chu,
                ]);
            }

            return $newCTDT;
        });
    }

    /**
     * Gửi CTDT phê duyệt
     */
    public function sendForApproval(ChuongTrinhDaoTao $ctdt, $reason = null): void
    {
        $ctdt->update(['trang_thai' => 'pending']);
    }

    /**
     * Phê duyệt CTDT
     */
    public function approve(ChuongTrinhDaoTao $ctdt, $reason = null): void
    {
        $ctdt->update(['trang_thai' => 'approved']);
    }

    /**
     * Công bố CTDT
     */
    public function publish(ChuongTrinhDaoTao $ctdt): void
    {
        $ctdt->update(['trang_thai' => 'published']);
    }

    /**
     * Trả lại để chỉnh sửa
     */
    public function rejectForRevision(ChuongTrinhDaoTao $ctdt, $lyDo): void
    {
        $ctdt->update([
            'trang_thai' => 'draft',
            'ly_do_tra_ve' => $lyDo,
        ]);
    }

    /**
     * Tạo mã CTDT unique
     */
    private function generateUniqueMaCTDT($khoaId, $nienKhoaId): string
    {
        $khoa = $sourceCTDT->khoa;
        $nienKhoa = $sourceCTDT->nienKhoa;

        $ma = $khoa->ma . '-' . $nienKhoa->ma;
        $count = ChuongTrinhDaoTao::where('ma_ctdt', 'like', $ma . '%')->count();

        if ($count > 0) {
            $ma = $ma . '-' . ($count + 1);
        }

        return $ma;
    }

    /**
     * Validate ràng buộc tiên quyết (không vòng lặp)
     */
    public function validateRangBuoc(ChuongTrinhDaoTao $ctdt, $hocPhanId, $lienQuanHpId): bool
    {
        if ($hocPhanId == $lienQuanHpId) {
            throw new Exception('Không được tạo ràng buộc cho chính học phần');
        }

        // TODO: Implement cycle detection
        return true;
    }

    /**
     * Lấy danh sách CTDT chờ phê duyệt
     */
    public function getPendingCTDTs()
    {
        return ChuongTrinhDaoTao::where('trang_thai', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Lấy danh sách CTDT sắp hết hiệu lực
     */
    public function getExpiringSoonCTDTs()
    {
        $date = now()->addMonths(3);

        return ChuongTrinhDaoTao::where('hieu_luc_den', '<=', $date)
            ->where('hieu_luc_den', '>=', now())
            ->where('trang_thai', 'published')
            ->get();
    }
}
