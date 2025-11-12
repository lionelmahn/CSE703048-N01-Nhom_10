@extends('layouts.app')

@section('title', 'Quản lý Bộ môn')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Quản lý Bộ môn</h1>
    @can('create', App\Models\BoMon::class)
        <a href="{{ route('bo-mon.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo Bộ môn mới
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
                        <th>Mã Bộ môn</th>
                        <th>Tên Bộ môn</th>
                        <th>Khoa</th>
                        <th>Mô tả</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boMons as $boMon)
                        <tr>
                            <td><strong>{{ $boMon->ma }}</strong></td>
                            <td>{{ $boMon->ten }}</td>
                            <td>{{ $boMon->khoa->ten ?? 'N/A' }}</td>
                            <td>{{ Str::limit($boMon->mo_ta, 50) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('bo-mon.show', $boMon) }}" class="btn btn-info" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('update', $boMon)
                                        <a href="{{ route('bo-mon.edit', $boMon) }}" class="btn btn-warning" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $boMon)
                                        <button type="button" class="btn btn-danger" title="Xóa"
                                            onclick="if(confirm('Bạn có chắc chắn muốn xóa bộ môn này?')) { document.getElementById('delete-form-{{ $boMon->id }}').submit(); }">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $boMon->id }}" action="{{ route('bo-mon.destroy', $boMon) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Chưa có bộ môn nào. 
                                @can('create', App\Models\BoMon::class)
                                    <a href="{{ route('bo-mon.create') }}">Tạo bộ môn mới</a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $boMons->links() }}
        </div>
    </div>
</div>
@endsection
