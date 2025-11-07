@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Quản lý Chuyên ngành</h1>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('chuyen-nganh.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Chuyên ngành
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
                        <th>Mã</th>
                        <th>Tên Chuyên ngành</th>
                        <th>Ngành</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chuyenNganhs as $chuyenNganh)
                        <tr>
                            {{-- Fixed field names to match database schema --}}
                            <td>{{ $chuyenNganh->ma }}</td>
                            <td>{{ $chuyenNganh->ten }}</td>
                            <td>{{ $chuyenNganh->nganh->ten ?? '-' }}</td>
                            <td>
                                <a href="{{ route('chuyen-nganh.show', $chuyenNganh) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('chuyen-nganh.edit', $chuyenNganh) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('chuyen-nganh.destroy', $chuyenNganh) }}" method="POST" class="d-inline">
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
                            <td colspan="4" class="text-center">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {{ $chuyenNganhs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
