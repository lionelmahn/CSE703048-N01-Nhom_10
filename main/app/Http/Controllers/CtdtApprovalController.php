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
        $ctdts = ChuongTrinhDaoTao::where('trang_thai', 'pending')
            ->with(['khoa', 'nganh', 'nienKhoa', 'nguoiTao', 'heDaoTao', 'chuyenNganh'])
            ->paginate(15);

        return view('ctdt-approval.pending', compact('ctdts'));
    }

    public function approve(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('approve', $ctdt);

        if ($ctdt->trang_thai !== 'pending') {
            return back()->with('error', 'Chỉ có thể phê duyệt CTĐT ở trạng thái chờ duyệt');
        }

        $ctdt->update(['trang_thai' => 'approved']);


        return back()->with('success', 'Phê duyệt CTĐT thành công');
    }

    public function publish(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('publish', $ctdt);

        if ($ctdt->trang_thai !== 'approved') {
            return back()->with('error', 'Chỉ có thể công bố CTĐT đã được phê duyệt');
        }

        $ctdt->update(['trang_thai' => 'published']);


        return back()->with('success', 'Công bố CTĐT thành công');
    }

    public function reject(Request $request, ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('approve', $ctdt);

        $request->validate(['ly_do' => 'required|string|max:500']);

        $ctdt->update(['trang_thai' => 'draft']);


        return back()->with('success', 'Từ chối CTĐT thành công');
    }
}
