<?php

namespace App\Http\Controllers;

use App\Models\Nganh;
use Illuminate\Http\Request;

class NganhController extends Controller
{
    public function index()
    {
        $nganhs = Nganh::with(['chuongTrinhDaoTaos'])->paginate(20);
        return view('nganh.index', compact('nganhs'));
    }

    public function create()
    {
        return view('nganh.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma' => 'required|string|unique:nganh|max:50',
            'ten' => 'required|string|max:255',
        ]);

        $nganh = Nganh::create($validated);

        return redirect()->route('nganh.show', $nganh)->with('success', 'Tạo ngành thành công');
    }

    public function show(Nganh $nganh)
    {
        $nganh->load(['chuongTrinhDaoTaos']);
        return view('nganh.show', compact('nganh'));
    }

    public function edit(Nganh $nganh)
    {
        return view('nganh.edit', compact('nganh'));
    }

    public function update(Request $request, Nganh $nganh)
    {
        $validated = $request->validate([
            'ma' => 'required|string|max:50|unique:nganh,ma,' . $nganh->id,
            'ten' => 'required|string|max:255',
        ]);

        $nganh->update($validated);

        return redirect()->route('nganh.show', $nganh)->with('success', 'Cập nhật ngành thành công');
    }

    public function destroy(Nganh $nganh)
    {
        // BR1: Check if nganh has active CTDTs
        $activeCTDTs = $nganh->chuongTrinhDaoTaos()
            ->whereIn('trang_thai', ['cho_phe_duyet', 'da_phe_duyet'])
            ->count();

        if ($activeCTDTs > 0) {
            return redirect()->route('nganh.index')
                ->with('error', "Không thể xóa ngành. Còn {$activeCTDTs} chương trình đào tạo đang hoạt động.");
        }

        // BR2: Soft delete - mark as inactive instead of deleting
        $nganh->update(['active' => false]);

        return redirect()->route('nganh.index')
            ->with('success', 'Đã ngừng hoạt động ngành. Dữ liệu lịch sử được giữ nguyên.');
    }

    public function toggleActive(Nganh $nganh)
    {
        // If deactivating, check constraints
        if ($nganh->active && !$nganh->canBeDeactivated()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể ngừng hoạt động ngành này. Còn chương trình đào tạo đang hoạt động.'
            ], 422);
        }

        $nganh->active = !$nganh->active;
        $nganh->save();

        $status = $nganh->active ? 'Hoạt động' : 'Ngừng hoạt động';

        return response()->json([
            'success' => true,
            'active' => $nganh->active,
            'message' => "Đã chuyển ngành sang trạng thái: {$status}"
        ]);
    }
}
