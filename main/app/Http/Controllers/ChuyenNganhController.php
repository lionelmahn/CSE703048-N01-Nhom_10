<?php

namespace App\Http\Controllers;

use App\Models\ChuyenNganh;
use App\Models\Nganh;
use Illuminate\Http\Request;

class ChuyenNganhController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', ChuyenNganh::class);

        $chuyenNganhs = ChuyenNganh::with('nganh')->paginate(20);

        return view('chuyen-nganh.index', compact('chuyenNganhs'));
    }

    public function create()
    {
        $this->authorize('create', ChuyenNganh::class);

        $nganhs = Nganh::orderBy('ten')->get();

        return view('chuyen-nganh.create', compact('nganhs'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', ChuyenNganh::class);

        $validated = $request->validate([
            'ma' => 'required|string|max:20|unique:chuyen_nganh,ma',
            'ten' => 'required|string|max:255',
            'nganh_id' => 'required|exists:nganh,id',
        ]);

        ChuyenNganh::create($validated);

        return redirect()->route('chuyen-nganh.index')
            ->with('success', 'Chuyên ngành đã được tạo thành công.');
    }

    public function show(ChuyenNganh $chuyenNganh)
    {
        $this->authorize('view', $chuyenNganh);

        $chuyenNganh->load('nganh');

        return view('chuyen-nganh.show', compact('chuyenNganh'));
    }

    public function edit(ChuyenNganh $chuyenNganh)
    {
        $this->authorize('update', $chuyenNganh);

        $nganhs = Nganh::orderBy('ten')->get();

        return view('chuyen-nganh.edit', compact('chuyenNganh', 'nganhs'));
    }

    public function update(Request $request, ChuyenNganh $chuyenNganh)
    {
        $this->authorize('update', $chuyenNganh);

        $validated = $request->validate([
            'ma' => 'required|string|max:20|unique:chuyen_nganh,ma,' . $chuyenNganh->id,
            'ten' => 'required|string|max:255',
            'nganh_id' => 'required|exists:nganh,id',
        ]);

        $chuyenNganh->update($validated);

        return redirect()->route('chuyen-nganh.index')
            ->with('success', 'Chuyên ngành đã được cập nhật thành công.');
    }

    public function destroy(ChuyenNganh $chuyenNganh)
    {
        $this->authorize('delete', $chuyenNganh);

        $chuyenNganh->delete();

        return redirect()->route('chuyen-nganh.index')
            ->with('success', 'Chuyên ngành đã được xóa thành công.');
    }
}
