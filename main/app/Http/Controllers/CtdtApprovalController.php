<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CtdtApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function pending()
    {
        $ctdts = ChuongTrinhDaoTao::where('trang_thai', 'cho_phe_duyet')
            ->with(['khoa', 'nganh', 'nienKhoa', 'nguoiTao', 'heDaoTao', 'chuyenNganh', 'bacHoc', 'loaiHinhDaoTao', 'khoaHoc'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('ctdt-approval.pending', compact('ctdts'));
    }

    public function approve(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('approve', $ctdt);

        // BR3: Only allow approve if status is "Chờ phê duyệt"
        if ($ctdt->trang_thai !== 'cho_phe_duyet') {
            return back()->with('error', 'Chỉ có thể phê duyệt CTĐT ở trạng thái "Chờ phê duyệt".');
        }

        // BR4: Update to "Đã phê duyệt" and lock permanently
        $ctdt->update([
            'trang_thai' => 'da_phe_duyet',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'ly_do_tra_ve' => null,
        ]);

        // TODO: Send notification to Khoa

        return back()->with('success', 'Đã phê duyệt thành công. CTĐT đã được ban hành và bị khóa vĩnh viễn.');
    }

    public function publish(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('publish', $ctdt);

        if ($ctdt->trang_thai !== 'da_phe_duyet') {
            return back()->with('error', 'Chỉ có thể công bố CTĐT đã được phê duyệt');
        }

        $ctdt->update(['trang_thai' => 'published']);


        return back()->with('success', 'Công bố CTĐT thành công');
    }

    public function reject(Request $request, ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('approve', $ctdt);

        // BR3: Only allow reject if status is "Chờ phê duyệt"
        if ($ctdt->trang_thai !== 'cho_phe_duyet') {
            return back()->with('error', 'Chỉ có thể yêu cầu chỉnh sửa CTĐT ở trạng thái "Chờ phê duyệt".');
        }

        // BR2: Validation - lý do bắt buộc
        $request->validate([
            'ly_do_tra_ve' => 'required|string|min:10',
        ], [
            'ly_do_tra_ve.required' => 'Vui lòng nhập nội dung yêu cầu chỉnh sửa.',
            'ly_do_tra_ve.min' => 'Nội dung yêu cầu chỉnh sửa phải có ít nhất 10 ký tự.',
        ]);

        // BR5: Update to "Cần chỉnh sửa" and unlock for Khoa
        $ctdt->update([
            'trang_thai' => 'can_chinh_sua',
            'ly_do_tra_ve' => $request->ly_do_tra_ve,
        ]);

        // TODO: Send notification to Khoa with reason

        return back()->with('success', 'Đã gửi yêu cầu chỉnh sửa thành công. CTĐT đã được mở khóa cho Khoa.');
    }
}
