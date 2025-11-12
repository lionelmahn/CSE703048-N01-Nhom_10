@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý Hệ Đào Tạo</h1>
        @can('create', App\Models\HeDaoTao::class)
            <a href="{{ route('he-dao-tao.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm Hệ Đào Tạo
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
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tên Hệ Đào Tạo</th>
                            {{-- Changed from Số Ngành to Số CTĐT --}}
                            <th>Số CTĐT</th>
                            <th>Ngày Tạo</th>
                            <th class="text-end">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($heDaoTaos as $he)
                            <tr>
                                <td><strong>{{ $he->ma }}</strong></td>
                                <td>{{ $he->ten }}</td>
                                <td>
                                    {{-- Show CTDT count instead of nganhs --}}
                                    <span class="badge bg-info">{{ $he->chuong_trinh_dao_taos_count ?? 0 }}</span>
                                </td>
                                <td>{{ $he->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @can('update', $he)
                                            <a href="{{ route('he-dao-tao.edit', $he) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $he)
                                            <button type="button" class="btn btn-danger" 
                                                    onclick="if(confirm('Bạn có chắc chắn muốn xóa?')) document.getElementById('delete-form-{{ $he->id }}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                    @can('delete', $he)
                                        <form id="delete-form-{{ $he->id }}" action="{{ route('he-dao-tao.destroy', $he) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Chưa có hệ đào tạo nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $heDaoTaos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
