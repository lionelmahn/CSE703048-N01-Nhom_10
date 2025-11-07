@extends('layouts.app')

@section('title', 'Chương trình Đào tạo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Chương trình Đào tạo</h1>
    @can('create', App\Models\ChuongTrinhDaoTao::class)
    <a href="{{ route('ctdt.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo mới
    </a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã CTĐT</th>
                    <th>Tên</th>
                    <th>Khoa</th>
                    <th>Niên khóa</th>
                    <th>Trạng thái</th>
                    <th>Hiệu lực từ</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ctdts as $ctdt)
                <tr>
                    <td><strong>{{ $ctdt->ma_ctdt }}</strong></td>
                    <td>{{ $ctdt->ten }}</td>
                    <td>{{ $ctdt->khoa->ten }}</td>
                    <td>{{ $ctdt->nienKhoa->ma }}</td>
                    <td>
                        <span class="badge-status status-{{ $ctdt->trang_thai }}">
                            {{ ucfirst(str_replace('_', ' ', $ctdt->trang_thai)) }}
                        </span>
                    </td>
                    <td>{{ $ctdt->hieu_luc_tu->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('ctdt.show', $ctdt) }}" class="btn btn-sm btn-info" title="Xem">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('update', $ctdt)
                        <a href="{{ route('ctdt.edit', $ctdt) }}" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('delete', $ctdt)
                        <form method="POST" action="{{ route('ctdt.destroy', $ctdt) }}" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn?');">
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
                    <td colspan="7" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-light">
        {{ $ctdts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
