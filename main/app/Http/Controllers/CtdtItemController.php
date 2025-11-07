<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use App\Models\HocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CtdtItemController extends Controller
{
    public function addHocPhan(Request $request, ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);
        
        $validated = $request->validate([
            'hoc_phan_id' => 'required|exists:hoc_phan,id',
            'khoi_id' => 'nullable|exists:khoi_kien_thuc,id',
            'hoc_ky' => 'nullable|integer|min:1|max:8',
            'loai' => 'required|in:bat_buoc,tu_chon',
            'thu_tu' => 'nullable|integer',
            'ghi_chu' => 'nullable|string',
        ]);
        
        $existsHp = $ctdt->hocPhans()->where('hoc_phan_id', $validated['hoc_phan_id'])->exists();
        if ($existsHp) {
            return back()->with('error', 'Học phần này đã có trong CTĐT');
        }
        
        $maxThuTu = $ctdt->hocPhans()->max('ctdt_hoc_phan.thu_tu') ?? 0;
        $validated['thu_tu'] = $validated['thu_tu'] ?? $maxThuTu + 1;
        
        $ctdt->hocPhans()->attach($validated['hoc_phan_id'], [
            'khoi_id' => $validated['khoi_id'],
            'hoc_ky' => $validated['hoc_ky'],
            'loai' => $validated['loai'],
            'thu_tu' => $validated['thu_tu'],
            'ghi_chu' => $validated['ghi_chu'],
        ]);
        
        activity('ctdt_hoc_phan')
            ->causedBy(Auth::user())
            ->performedOn($ctdt)
            ->log('Thêm học phần');
        
        return back()->with('success', 'Thêm học phần thành công');
    }
    
    public function removeHocPhan(Request $request, ChuongTrinhDaoTao $ctdt, HocPhan $hocPhan)
    {
        $this->authorize('update', $ctdt);
        
        $ctdt->hocPhans()->detach($hocPhan->id);
        
        activity('ctdt_hoc_phan')
            ->causedBy(Auth::user())
            ->performedOn($ctdt)
            ->log('Loại bỏ học phần');
        
        return back()->with('success', 'Loại bỏ học phần thành công');
    }
    
    public function updateOrder(Request $request, ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);
        
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:ctdt_hoc_phan,id',
            'order.*.thu_tu' => 'required|integer',
        ]);
        
        foreach ($validated['order'] as $item) {
            $ctdt->hocPhans()->wherePivot('id', $item['id'])->update(['thu_tu' => $item['thu_tu']]);
        }
        
        activity('ctdt_hoc_phan')
            ->causedBy(Auth::user())
            ->performedOn($ctdt)
            ->log('Cập nhật thứ tự học phần');
        
        return response()->json(['success' => true]);
    }
}
