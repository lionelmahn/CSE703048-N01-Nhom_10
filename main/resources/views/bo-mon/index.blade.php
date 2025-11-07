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
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('bo-mon.show', $boMon) }}" class="btn btn-outline-info" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('update', $boMon)
                                        <a href="{{ route('bo-mon.edit', $boMon) }}" class="btn btn-outline-primary" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $boMon)
                                        <form action="{{ route('bo-mon.destroy', $boMon) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa bộ môn này?')">
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
