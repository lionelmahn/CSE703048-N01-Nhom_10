@extends('layouts.app')

@section('title', 'Bậc học')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Bậc học</h1>
    <a href="{{ route('bac-hoc.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo mới
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 100px;">Mã</th>
                    <th>Tên bậc học</th>
                    <th style="width: 150px;">Số CTĐT</th>
                    <th style="width: 200px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bacHocs as $bacHoc)
                <tr>
                    <td><strong>{{ $bacHoc->ma }}</strong></td>
                    <td>{{ $bacHoc->ten }}</td>
                    <td>
                        <span class="badge bg-info">{{ $bacHoc->chuongTrinhDaoTaos()->count() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('bac-hoc.edit', $bacHoc) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form method="POST" action="{{ route('bac-hoc.destroy', $bacHoc) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa bậc học này?');">
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
                        Chưa có bậc học nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($bacHocs->hasPages())
    <div class="card-footer bg-light">
        {{ $bacHocs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
