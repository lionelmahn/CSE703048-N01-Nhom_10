<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\ChuyenNganh;
use App\Models\HeDaoTao;
use App\Models\NienKhoa;
use App\Models\HocPhan;
use App\Http\Requests\StoreCTDTRequest;
use App\Http\Requests\UpdateCTDTRequest;
use Illuminate\Support\Facades\Auth;

class CtdtController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = ChuongTrinhDaoTao::query();

        if ($user->role !== 'admin') {
            $query->where('khoa_id', $user->khoa_id);
        }

        $ctdts = $query->with(['khoa', 'nganh', 'chuyenNganh', 'heDaoTao', 'nienKhoa', 'creator'])
            ->paginate(15);

        return view('ctdt.index', compact('ctdts'));
    }

    public function create()
    {
        $user = Auth::user();
        $khoasQuery = Khoa::query();

        if ($user->role !== 'admin') {
            $khoasQuery->where('id', $user->khoa_id);
        }

        $khoas = $khoasQuery->get();
        $nganhs = Nganh::all();
        $chuyenNganhs = ChuyenNganh::all();
        $heDaoTaos = HeDaoTao::all();
        $nienKhoas = NienKhoa::all();

        return view('ctdt.create', compact('khoas', 'nganhs', 'chuyenNganhs', 'heDaoTaos', 'nienKhoas'));
    }

    public function store(StoreCTDTRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::id();

        $ctdt = ChuongTrinhDaoTao::create($validated);


        return redirect()->route('ctdt.show', $ctdt)->with('success', 'Tạo CTĐT thành công');
    }

    public function show(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('view', $ctdt);

        $ctdt->load([
            'khoi' => function ($query) {
                $query->orderBy('thu_tu');
            },
            'hocPhans' => function ($query) {
                $query->orderBy('thu_tu');
            },
            'rangBuocs',
            'tuongDuongs'
        ]);

        return view('ctdt.show', compact('ctdt'));
    }

    public function edit(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);

        $user = Auth::user();
        $khoasQuery = Khoa::query();

        if ($user->role !== 'admin') {
            $khoasQuery->where('id', $user->khoa_id);
        }

        $khoas = $khoasQuery->get();
        $heDaoTaos = HeDaoTao::all();
        $nienKhoas = NienKhoa::all();
        $nganhs = $ctdt->khoa_id ? Nganh::where('he_dao_tao_id', $ctdt->he_dao_tao_id)->get() : [];
        $chuyenNganhs = $ctdt->nganh_id ? ChuyenNganh::where('nganh_id', $ctdt->nganh_id)->get() : [];

        return view('ctdt.edit', compact('ctdt', 'khoas', 'heDaoTaos', 'nienKhoas', 'nganhs', 'chuyenNganhs'));
    }

    public function update(UpdateCTDTRequest $request, ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);

        $validated = $request->validated();
        $ctdt->update($validated);


        return redirect()->route('ctdt.show', $ctdt)->with('success', 'Cập nhật CTĐT thành công');
    }

    public function destroy(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('delete', $ctdt);

        $ctdt->delete();


        return redirect()->route('ctdt.index')->with('success', 'Xóa CTĐT thành công');
    }

    public function clone(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('create', ChuongTrinhDaoTao::class);

        $newCtdt = $ctdt->replicate(['trang_thai', 'created_by']);
        $newCtdt->ma_ctdt = $ctdt->ma_ctdt . '_COPY_' . now()->timestamp;
        $newCtdt->trang_thai = 'draft';
        $newCtdt->created_by = Auth::id();
        $newCtdt->save();

        // Clone relationships
        foreach ($ctdt->hocPhans as $hocPhan) {
            $newCtdt->hocPhans()->attach($hocPhan->id, [
                'khoi_id' => $hocPhan->pivot->khoi_id,
                'hoc_ky' => $hocPhan->pivot->hoc_ky,
                'loai' => $hocPhan->pivot->loai,
                'thu_tu' => $hocPhan->pivot->thu_tu,
                'ghi_chu' => $hocPhan->pivot->ghi_chu,
            ]);
        }


        return redirect()->route('ctdt.edit', $newCtdt)->with('success', 'Sao chép CTĐT thành công');
    }

    public function sendForApproval(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);

        if ($ctdt->trang_thai !== 'draft') {
            return back()->with('error', 'Chỉ có thể gửi duyệt CTĐT ở trạng thái nháp');
        }

        $ctdt->update(['trang_thai' => 'pending']);


        return back()->with('success', 'Gửi phê duyệt thành công');
    }
}
