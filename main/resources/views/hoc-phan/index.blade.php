@extends('layouts.app')

@section('title', 'Học phần')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Học phần</h1>
    @can('create', App\Models\HocPhan::class)
    <a href="{{ route('hoc-phan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo mới
    </a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã HP</th>
                    <th>Tên</th>
                    <th>Tín chỉ</th>
                    <th>Khoa</th>
                    <th>Trạng thái</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($hocPhans as $hocPhan)
                <tr>
                    <td><strong>{{ $hocPhan->ma_hp }}</strong></td>
                    <td>{{ $hocPhan->ten_hp }}</td>
                    <td>{{ $hocPhan->so_tinchi }}</td>
                    <td>{{ $hocPhan->khoa->ten }}</td>
                    <td>
                        <span class="badge @if($hocPhan->active) bg-success @else bg-danger @endif">
                            {{ $hocPhan->active ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('hoc-phan.show', $hocPhan) }}" class="btn btn-sm btn-info" title="Xem">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('update', $hocPhan)
                        <a href="{{ route('hoc-phan.edit', $hocPhan) }}" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('delete', $hocPhan)
                        <form method="POST" action="{{ route('hoc-phan.destroy', $hocPhan) }}" style="display: inline;" onsubmit="return confirm('Xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-light">
        {{ $hocPhans->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
