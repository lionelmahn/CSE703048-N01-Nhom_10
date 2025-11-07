@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Quản lý Khóa học</h1>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('khoa-hoc.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Khóa học
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã Khóa học</th>
                        <th>Niên khóa</th>
                        <th>Năm bắt đầu</th>
                        <th>Năm kết thúc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khoaHocs as $khoaHoc)
                        <tr>
                            {{-- Fixed field names to match database schema --}}
                            <td>{{ $khoaHoc->ma }}</td>
                            <td>{{ $khoaHoc->nienKhoa->ma ?? '-' }}</td>
                            <td>{{ $khoaHoc->nienKhoa->nam_bat_dau ?? '-' }}</td>
                            <td>{{ $khoaHoc->nienKhoa->nam_ket_thuc ?? '-' }}</td>
                            <td>
                                <a href="{{ route('khoa-hoc.show', $khoaHoc) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('khoa-hoc.edit', $khoaHoc) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('khoa-hoc.destroy', $khoaHoc) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $khoaHocs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
