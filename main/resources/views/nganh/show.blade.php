@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chi tiết ngành: {{ $nganh->ten }}</h1>
        <div>
            @can('update', $nganh)
            <a href="{{ route('nganh.edit', $nganh) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            @endcan
            <a href="{{ route('nganh.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Mã ngành:</th>
                            <td><strong class="text-primary">{{ $nganh->ma }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tên ngành:</th>
                            <td>{{ $nganh->ten }}</td>
                        </tr>
                        <tr>
                            <th>Hệ đào tạo:</th>
                            <td><span class="badge bg-info">{{ $nganh->heDaoTao->ten ?? 'N/A' }}</span></td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td>{{ $nganh->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật:</th>
                            <td>{{ $nganh->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Chương trình đào tạo</h5>
                </div>
                <div class="card-body">
                    @if($nganh->chuongTrinhDaoTaos->count() > 0)
                        <div class="list-group">
                            @foreach($nganh->chuongTrinhDaoTaos as $ctdt)
                            <a href="{{ route('ctdt.show', $ctdt) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $ctdt->ten }}</h6>
                                    <small>
                                        @if($ctdt->trang_thai === 'cong_bo')
                                            <span class="badge bg-success">Công bố</span>
                                        @elseif($ctdt->trang_thai === 'da_duyet')
                                            <span class="badge bg-primary">Đã duyệt</span>
                                        @elseif($ctdt->trang_thai === 'cho_duyet')
                                            <span class="badge bg-warning">Chờ duyệt</span>
                                        @else
                                            <span class="badge bg-secondary">Nháp</span>
                                        @endif
                                    </small>
                                </div>
                                <p class="mb-1 text-muted small">{{ $ctdt->mo_ta }}</p>
                                <small class="text-muted">Niên khóa: {{ $ctdt->nienKhoa->nam ?? 'N/A' }}</small>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">Chưa có chương trình đào tạo nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @can('delete', $nganh)
    <div class="card shadow-sm border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Xóa ngành</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Xóa ngành này sẽ ảnh hưởng đến {{ $nganh->chuongTrinhDaoTaos->count() }} chương trình đào tạo liên quan.</p>
            <form action="{{ route('nganh.destroy', $nganh) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngành này? Hành động này không thể hoàn tác!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Xóa ngành
                </button>
            </form>
        </div>
    </div>
    @endcan
</div>
@endsection
