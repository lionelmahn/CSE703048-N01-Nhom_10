<?php

namespace App\Http\Controllers;

use App\Models\HeDaoTao;
use Illuminate\Http\Request;

class HeDaoTaoController extends Controller
{
    public function index()
    {
        $heDaoTaos = HeDaoTao::withCount('chuongTrinhDaoTaos')->paginate(20);
        return view('he-dao-tao.index', compact('heDaoTaos'));
    }
    
    public function create()
    {
        return view('he-dao-tao.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma' => 'required|string|unique:he_dao_tao|max:50',
            'ten' => 'required|string|max:255',
        ]);
        
        $he = HeDaoTao::create($validated);
        
        
        return redirect()->route('he-dao-tao.show', $he)->with('success', 'Tạo hệ đào tạo thành công');
    }
    
    public function show(HeDaoTao $heDaoTao)
    {
        $heDaoTao->load('chuongTrinhDaoTaos');
        return view('he-dao-tao.show', compact('heDaoTao'));
    }
    
    public function edit(HeDaoTao $heDaoTao)
    {
        return view('he-dao-tao.edit', compact('heDaoTao'));
    }
    
    public function update(Request $request, HeDaoTao $heDaoTao)
    {
        $validated = $request->validate([
            'ma' => 'required|string|max:50|unique:he_dao_tao,ma,' . $heDaoTao->id,
            'ten' => 'required|string|max:255',
        ]);
        
        $heDaoTao->update($validated);
        
        
        return redirect()->route('he-dao-tao.show', $heDaoTao)->with('success', 'Cập nhật hệ đào tạo thành công');
    }
    
    public function destroy(HeDaoTao $heDaoTao)
    {
        $heDaoTao->delete();
        
        
        return redirect()->route('he-dao-tao.index')->with('success', 'Xóa hệ đào tạo thành công');
    }
}
