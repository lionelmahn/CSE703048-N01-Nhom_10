@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chi tiết chuyên ngành: {{ $chuyenNganh->ten }}</h1>
        <div>
            @can('update', $chuyenNganh)
            <a href="{{ route('chuyen-nganh.edit', $chuyenNganh) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            @endcan
            <a href="{{ route('chuyen-nganh.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
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
                            <th width="150">Mã chuyên ngành:</th>
                            <td><strong class="text-primary">{{ $chuyenNganh->ma }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tên chuyên ngành:</th>
                            <td>{{ $chuyenNganh->ten }}</td>
                        </tr>
                        <tr>
                            <th>Ngành:</th>
                            <td>
                                <a href="{{ route('nganh.show', $chuyenNganh->nganh) }}" class="text-decoration-none">
                                    <span class="badge bg-info">{{ $chuyenNganh->nganh->ten }}</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Mã ngành:</th>
                            <td>{{ $chuyenNganh->nganh->ma }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td>{{ $chuyenNganh->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật:</th>
                            <td>{{ $chuyenNganh->updated_at->format('d/m/Y H:i') }}</td>
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
                    @if($chuyenNganh->chuongTrinhDaoTaos->count() > 0)
                        <div class="list-group">
                            @foreach($chuyenNganh->chuongTrinhDaoTaos as $ctdt)
                            <a href="{{ route('ctdt.show', $ctdt) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $ctdt->ten }}</h6>
                                    <small>
                                        @if($ctdt->trang_thai === 'da_phe_duyet')
                                            <span class="badge bg-success">Đã phê duyệt</span>
                                        @elseif($ctdt->trang_thai === 'cho_phe_duyet')
                                            <span class="badge bg-warning">Chờ phê duyệt</span>
                                        @elseif($ctdt->trang_thai === 'can_chinh_sua')
                                            <span class="badge bg-danger">Cần chỉnh sửa</span>
                                        @else
                                            <span class="badge bg-secondary">Bản nháp</span>
                                        @endif
                                    </small>
                                </div>
                                <p class="mb-1 text-muted small">{{ $ctdt->mo_ta ?? 'Không có mô tả' }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> Niên khóa: {{ $ctdt->nienKhoa->ten ?? 'N/A' }}
                                    | <i class="fas fa-graduation-cap"></i> Khóa học: {{ $ctdt->khoaHoc->ten ?? 'N/A' }}
                                </small>
                            </a>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle"></i> 
                                Tổng số chương trình đào tạo: <strong>{{ $chuyenNganh->chuongTrinhDaoTaos->count() }}</strong>
                            </p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có chương trình đào tạo nào cho chuyên ngành này.</p>
                            @can('create', App\Models\ChuongTrinhDaoTao::class)
                            <a href="{{ route('ctdt.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Tạo CTĐT mới
                            </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @can('delete', $chuyenNganh)
    <div class="card shadow-sm border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Khu vực nguy hiểm</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                Xóa chuyên ngành này sẽ ảnh hưởng đến <strong>{{ $chuyenNganh->chuongTrinhDaoTaos->count() }}</strong> chương trình đào tạo liên quan.
            </p>
            @if($chuyenNganh->chuongTrinhDaoTaos->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle"></i> 
                    Không thể xóa chuyên ngành này vì còn chương trình đào tạo đang sử dụng. 
                    Vui lòng xóa hoặc chuyển các CTĐT sang chuyên ngành khác trước.
                </div>
            @else
                <form action="{{ route('chuyen-nganh.destroy', $chuyenNganh) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chuyên ngành này? Hành động này không thể hoàn tác!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Xóa chuyên ngành
                    </button>
                </form>
            @endif
        </div>
    </div>
    @endcan
</div>
@endsection
