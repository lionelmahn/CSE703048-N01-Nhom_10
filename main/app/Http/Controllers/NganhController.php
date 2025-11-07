<?php

namespace App\Http\Controllers;

use App\Models\Nganh;
use App\Models\HeDaoTao;
use Illuminate\Http\Request;

class NganhController extends Controller
{
    public function index()
    {
        $nganhs = Nganh::with(['heDaoTao', 'chuongTrinhDaoTaos'])->paginate(20);
        return view('nganh.index', compact('nganhs'));
    }

    public function create()
    {
        $heDaoTaos = HeDaoTao::all();
        return view('nganh.create', compact('heDaoTaos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma' => 'required|string|unique:nganh|max:50',
            'ten' => 'required|string|max:255',
            'he_dao_tao_id' => 'required|exists:he_dao_tao,id',
        ]);

        $nganh = Nganh::create($validated);


        return redirect()->route('nganh.show', $nganh)->with('success', 'Tạo ngành thành công');
    }

    public function show(Nganh $nganh)
    {
        $nganh->load(['heDaoTao', 'chuongTrinhDaoTaos']);
        return view('nganh.show', compact('nganh'));
    }

    public function edit(Nganh $nganh)
    {
        $heDaoTaos = HeDaoTao::all();
        return view('nganh.edit', compact('nganh', 'heDaoTaos'));
    }

    public function update(Request $request, Nganh $nganh)
    {
        $validated = $request->validate([
            'ma' => 'required|string|max:50|unique:nganh,ma,' . $nganh->id,
            'ten' => 'required|string|max:255',
            'he_dao_tao_id' => 'required|exists:he_dao_tao,id',
        ]);

        $nganh->update($validated);


        return redirect()->route('nganh.show', $nganh)->with('success', 'Cập nhật ngành thành công');
    }

    public function destroy(Nganh $nganh)
    {
        $nganh->delete();


        return redirect()->route('nganh.index')->with('success', 'Xóa ngành thành công');
    }
}
