@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Quản lý Khối kiến thức</h1>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('khoi-kien-thuc.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 15%">Mã</th>
                            <th style="width: 55%">Tên khối kiến thức</th>
                            <th style="width: 15%" class="text-center">Số CTĐT</th>
                            <th style="width: 15%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($khoiKienThucs as $khoi)
                        <tr>
                            <td><strong>{{ $khoi->ma }}</strong></td>
                            <td>{{ $khoi->ten }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $khoi->ctdt_khois_count }}</span>
                            </td>
                            <td class="text-center">
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('khoi-kien-thuc.edit', $khoi) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('khoi-kien-thuc.destroy', $khoi) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa khối kiến thức này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">Chỉ xem</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Chưa có khối kiến thức nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $khoiKienThucs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
