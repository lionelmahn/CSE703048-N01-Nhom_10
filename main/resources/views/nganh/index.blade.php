@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý Ngành</h1>
        @can('create', App\Models\Nganh::class)
        <a href="{{ route('nganh.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm ngành mới
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ngành</th>
                            <th>Tên ngành</th>
                            <th>Hệ đào tạo</th>
                            <th>Số CTĐT</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nganhs as $nganh)
                        <tr>
                            <td><strong>{{ $nganh->ma }}</strong></td>
                            <td>
                                <a href="{{ route('nganh.show', $nganh) }}" class="text-decoration-none">
                                    {{ $nganh->ten }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $nganh->heDaoTao->ten ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $nganh->chuongTrinhDaoTaos->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('nganh.show', $nganh) }}" class="btn btn-outline-info" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('update', $nganh)
                                    <a href="{{ route('nganh.edit', $nganh) }}" class="btn btn-outline-primary" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
                                    @can('delete', $nganh)
                                    <form action="{{ route('nganh.destroy', $nganh) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngành này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Chưa có ngành nào. <a href="{{ route('nganh.create') }}">Thêm ngành mới</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $nganhs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
