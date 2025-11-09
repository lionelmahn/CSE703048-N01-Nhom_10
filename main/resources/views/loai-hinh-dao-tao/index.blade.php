@extends('layouts.app')

@section('title', 'Loại hình đào tạo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Loại hình đào tạo</h1>
    <a href="{{ route('loai-hinh-dao-tao.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo mới
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 100px;">Mã</th>
                    <th>Tên loại hình</th>
                    <th style="width: 150px;">Số CTĐT</th>
                    <th style="width: 200px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($loaiHinhDaoTaos as $loaiHinh)
                <tr>
                    <td><strong>{{ $loaiHinh->ma }}</strong></td>
                    <td>{{ $loaiHinh->ten }}</td>
                    <td>
                        <span class="badge bg-info">{{ $loaiHinh->chuongTrinhDaoTaos()->count() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('loai-hinh-dao-tao.edit', $loaiHinh) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form method="POST" action="{{ route('loai-hinh-dao-tao.destroy', $loaiHinh) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa loại hình đào tạo này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        Chưa có loại hình đào tạo nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($loaiHinhDaoTaos->hasPages())
    <div class="card-footer bg-light">
        {{ $loaiHinhDaoTaos->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
