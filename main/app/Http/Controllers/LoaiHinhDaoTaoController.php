<?php

namespace App\Http\Controllers;

use App\Models\LoaiHinhDaoTao;
use App\Helpers\CodeGenerator;
use Illuminate\Http\Request;

class LoaiHinhDaoTaoController extends Controller
{
    public function index()
    {
        $loaiHinhDaoTaos = LoaiHinhDaoTao::orderBy('ma')->paginate(15);
        return view('loai-hinh-dao-tao.index', compact('loaiHinhDaoTaos'));
    }

    public function create()
    {
        $suggestedCode = CodeGenerator::generateCode('loai_hinh_dao_tao', 'ma', 'LHDT', 2);
        return view('loai-hinh-dao-tao.create', compact('suggestedCode'));
    }

    public function store(Request $request)
    {
        if ($request->has('auto_generate_code') && $request->auto_generate_code == '1') {
            $code = CodeGenerator::generateCode('loai_hinh_dao_tao', 'ma', 'LHDT', 2);
            $request->merge(['ma' => $code]);
        }

        $validated = $request->validate([
            'ma' => 'required|string|max:10|unique:loai_hinh_dao_tao',
            'ten' => 'required|string|max:255',
        ]);

        LoaiHinhDaoTao::create($validated);

        return redirect()->route('loai-hinh-dao-tao.index')->with('success', 'Thêm loại hình đào tạo thành công');
    }

    public function edit(LoaiHinhDaoTao $loaiHinhDaoTao)
    {
        return view('loai-hinh-dao-tao.edit', compact('loaiHinhDaoTao'));
    }

    public function update(Request $request, LoaiHinhDaoTao $loaiHinhDaoTao)
    {
        $validated = $request->validate([
            'ma' => 'required|string|max:10|unique:loai_hinh_dao_tao,ma,' . $loaiHinhDaoTao->id,
            'ten' => 'required|string|max:255',
        ]);

        $loaiHinhDaoTao->update($validated);

        return redirect()->route('loai-hinh-dao-tao.index')->with('success', 'Cập nhật loại hình đào tạo thành công');
    }

    public function destroy(LoaiHinhDaoTao $loaiHinhDaoTao)
    {
        if ($loaiHinhDaoTao->chuongTrinhDaoTaos()->count() > 0) {
            return back()->with('error', 'Không thể xóa loại hình đào tạo đang được sử dụng');
        }

        $loaiHinhDaoTao->delete();

        return redirect()->route('loai-hinh-dao-tao.index')->with('success', 'Xóa loại hình đào tạo thành công');
    }
}
