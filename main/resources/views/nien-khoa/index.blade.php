@extends('layouts.app')

@section('title', 'Quản lý Niên khóa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý Niên khóa</h2>
    @can('create', App\Models\NienKhoa::class)
    <a href="{{ route('nien-khoa.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Thêm Niên khóa
    </a>
    @endcan
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Năm bắt đầu</th>
                        <th>Năm kết thúc</th>
                        <th>Tên đầy đủ</th>
                        <th>Số lượng sử dụng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nienKhoas as $nienKhoa)
                    <tr>
                        <td><strong>{{ $nienKhoa->ma }}</strong></td>
                        <td>{{ $nienKhoa->nam_bat_dau }}</td>
                        <td>{{ $nienKhoa->nam_ket_thuc }}</td>
                        <td>{{ $nienKhoa->ten }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $nienKhoa->chuongTrinhDaoTaos->count() }} CTĐT
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('update', $nienKhoa)
                                <a href="{{ route('nien-khoa.edit', $nienKhoa) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                
                                @can('delete', $nienKhoa)
                                <form action="{{ route('nien-khoa.destroy', $nienKhoa) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Xác nhận xóa niên khóa này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Chưa có niên khóa nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $nienKhoas->links() }}
        </div>
    </div>
</div>
@endsection
