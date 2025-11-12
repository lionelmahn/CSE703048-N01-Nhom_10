<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use App\Models\CtdtRangBuoc;
use App\Models\HocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CtdtRangBuocController extends Controller
{
    /**
     * Display the rang buoc management interface
     */
    public function index(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);
        
        // Load CTDT with its hoc phans
        $ctdt->load(['ctdtHocPhans.hocPhan', 'ctdtHocPhans.khoi']);
        
        return view('ctdt.rang-buoc', compact('ctdt'));
    }
    
    /**
     * Get rang buoc data for a specific hoc phan
     */
    public function getRangBuoc(ChuongTrinhDaoTao $ctdt, $hocPhanId)
    {
        $this->authorize('update', $ctdt);
        
        // Get all rang buoc for this hoc phan in this CTDT
        $rangBuocs = CtdtRangBuoc::where('ctdt_id', $ctdt->id)
            ->where('hoc_phan_id', $hocPhanId)
            ->with('lienQuanHocPhan')
            ->get()
            ->groupBy('kieu');
        
        return response()->json([
            'success' => true,
            'data' => $rangBuocs
        ]);
    }
    
    /**
     * Search hoc phan for rang buoc (from entire system)
     */
    public function searchHocPhan(Request $request)
    {
        $query = $request->get('q', '');
        
        $hocPhans = HocPhan::where('active', true)
            ->where(function($q) use ($query) {
                $q->where('ma_hp', 'like', "%{$query}%")
                  ->orWhere('ten_hp', 'like', "%{$query}%");
            })
            ->with(['khoa', 'boMon'])
            ->limit(20)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $hocPhans
        ]);
    }
    
    /**
     * Save all changes (add/delete rang buoc)
     */
    public function saveChanges(Request $request, ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);
        
        $request->validate([
            'changes' => 'required|array',
            'changes.*.action' => 'required|in:add,delete',
            'changes.*.hoc_phan_id' => 'required|exists:hoc_phan,id',
            'changes.*.lien_quan_hp_id' => 'required|exists:hoc_phan,id',
            'changes.*.kieu' => 'required|in:tien_quyet,song_hanh,thay_the'
        ]);
        
        try {
            DB::beginTransaction();
            
            $changes = $request->input('changes');
            $errors = [];
            
            // First, validate all changes
            foreach ($changes as $index => $change) {
                $hocPhanId = $change['hoc_phan_id'];
                $lienQuanHpId = $change['lien_quan_hp_id'];
                $kieu = $change['kieu'];
                
                // BR3: Check self-constraint
                if ($hocPhanId == $lienQuanHpId) {
                    $hocPhan = HocPhan::find($hocPhanId);
                    $errors[] = "Học phần '{$hocPhan->ten_hp}' không thể tự ràng buộc với chính nó.";
                    continue;
                }
                
                // BR2: Check circular constraint (only for tien_quyet and song_hanh)
                if ($change['action'] === 'add' && in_array($kieu, ['tien_quyet', 'song_hanh'])) {
                    if ($this->hasCircularConstraint($ctdt->id, $hocPhanId, $lienQuanHpId, $kieu)) {
                        $hocPhan = HocPhan::find($hocPhanId);
                        $lienQuanHp = HocPhan::find($lienQuanHpId);
                        $errors[] = "Lỗi ràng buộc vòng: '{$lienQuanHp->ten_hp}' đã có ràng buộc ngược với '{$hocPhan->ten_hp}'.";
                        continue;
                    }
                }
            }
            
            // If there are validation errors, return them
            if (!empty($errors)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi khi kiểm tra ràng buộc',
                    'errors' => $errors
                ], 422);
            }
            
            // Process all changes
            foreach ($changes as $change) {
                if ($change['action'] === 'add') {
                    // Check if already exists
                    $exists = CtdtRangBuoc::where('ctdt_id', $ctdt->id)
                        ->where('hoc_phan_id', $change['hoc_phan_id'])
                        ->where('lien_quan_hp_id', $change['lien_quan_hp_id'])
                        ->where('kieu', $change['kieu'])
                        ->exists();
                    
                    if (!$exists) {
                        CtdtRangBuoc::create([
                            'ctdt_id' => $ctdt->id,
                            'hoc_phan_id' => $change['hoc_phan_id'],
                            'lien_quan_hp_id' => $change['lien_quan_hp_id'],
                            'kieu' => $change['kieu'],
                            'ghi_chu' => $change['ghi_chu'] ?? null
                        ]);
                        
                        // BR4: Auto create reverse for 'thay_the'
                        if ($change['kieu'] === 'thay_the') {
                            $reverseExists = CtdtRangBuoc::where('ctdt_id', $ctdt->id)
                                ->where('hoc_phan_id', $change['lien_quan_hp_id'])
                                ->where('lien_quan_hp_id', $change['hoc_phan_id'])
                                ->where('kieu', 'thay_the')
                                ->exists();
                            
                            if (!$reverseExists) {
                                CtdtRangBuoc::create([
                                    'ctdt_id' => $ctdt->id,
                                    'hoc_phan_id' => $change['lien_quan_hp_id'],
                                    'lien_quan_hp_id' => $change['hoc_phan_id'],
                                    'kieu' => 'thay_the',
                                    'ghi_chu' => 'Tự động tạo (ràng buộc 2 chiều)'
                                ]);
                            }
                        }
                    }
                } elseif ($change['action'] === 'delete') {
                    CtdtRangBuoc::where('ctdt_id', $ctdt->id)
                        ->where('hoc_phan_id', $change['hoc_phan_id'])
                        ->where('lien_quan_hp_id', $change['lien_quan_hp_id'])
                        ->where('kieu', $change['kieu'])
                        ->delete();
                    
                    // Also delete reverse if it's 'thay_the'
                    if ($change['kieu'] === 'thay_the') {
                        CtdtRangBuoc::where('ctdt_id', $ctdt->id)
                            ->where('hoc_phan_id', $change['lien_quan_hp_id'])
                            ->where('lien_quan_hp_id', $change['hoc_phan_id'])
                            ->where('kieu', 'thay_the')
                            ->delete();
                    }
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ràng buộc thành công'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving rang buoc: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu ràng buộc',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check for circular constraints
     */
    private function hasCircularConstraint($ctdtId, $hocPhanId, $lienQuanHpId, $kieu)
    {
        // Check if lienQuanHpId already has hocPhanId as its constraint of same type
        $reverseExists = CtdtRangBuoc::where('ctdt_id', $ctdtId)
            ->where('hoc_phan_id', $lienQuanHpId)
            ->where('lien_quan_hp_id', $hocPhanId)
            ->where('kieu', $kieu)
            ->exists();
        
        if ($reverseExists) {
            return true;
        }
        
        // Check transitive circular constraint (A->B->C->A)
        // Get all constraints of lienQuanHpId
        $constraints = CtdtRangBuoc::where('ctdt_id', $ctdtId)
            ->where('hoc_phan_id', $lienQuanHpId)
            ->where('kieu', $kieu)
            ->pluck('lien_quan_hp_id')
            ->toArray();
        
        // Recursively check if any of them leads back to hocPhanId
        $visited = [$lienQuanHpId];
        return $this->checkCircularRecursive($ctdtId, $constraints, $hocPhanId, $kieu, $visited);
    }
    
    private function checkCircularRecursive($ctdtId, $constraints, $targetId, $kieu, &$visited)
    {
        foreach ($constraints as $constraintId) {
            if ($constraintId == $targetId) {
                return true; // Found circular
            }
            
            if (in_array($constraintId, $visited)) {
                continue; // Already visited, skip
            }
            
            $visited[] = $constraintId;
            
            // Get next level constraints
            $nextConstraints = CtdtRangBuoc::where('ctdt_id', $ctdtId)
                ->where('hoc_phan_id', $constraintId)
                ->where('kieu', $kieu)
                ->pluck('lien_quan_hp_id')
                ->toArray();
            
            if ($this->checkCircularRecursive($ctdtId, $nextConstraints, $targetId, $kieu, $visited)) {
                return true;
            }
        }
        
        return false;
    }
}
