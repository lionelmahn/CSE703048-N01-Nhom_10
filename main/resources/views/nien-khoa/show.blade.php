@extends('layouts.app')

@section('title', 'Chi tiết Niên khóa')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>{{ $nienKhoa->ten }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('nien-khoa.index') }}">Niên khóa</a></li>
                    <li class="breadcrumb-item active">{{ $nienKhoa->ma }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @can('update', $nienKhoa)
            <a href="{{ route('nien-khoa.edit', $nienKhoa) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            @endcan
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin chi tiết</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Mã niên khóa:</th>
                        <td><strong>{{ $nienKhoa->ma }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tên niên khóa:</th>
                        <td>{{ $nienKhoa->ten }}</td>
                    </tr>
                    <tr>
                        <th>Năm bắt đầu:</th>
                        <td>{{ $nienKhoa->nam_bat_dau }}</td>
                    </tr>
                    <tr>
                        <th>Năm kết thúc:</th>
                        <td>{{ $nienKhoa->nam_ket_thuc }}</td>
                    </tr>
                    <tr>
                        <th>Tạo lúc:</th>
                        <td>{{ $nienKhoa->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật lúc:</th>
                        <td>{{ $nienKhoa->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Chương trình đào tạo sử dụng niên khóa này</h5>
            </div>
            <div class="card-body">
                @if($nienKhoa->chuongTrinhDaoTaos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã CTĐT</th>
                                <th>Tên</th>
                                <th>Ngành</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nienKhoa->chuongTrinhDaoTaos as $ctdt)
                            <tr>
                                <td><strong>{{ $ctdt->ma_ctdt }}</strong></td>
                                <td>
                                    <a href="{{ route('ctdt.show', $ctdt) }}">
                                        {{ $ctdt->ten_ctdt }}
                                    </a>
                                </td>
                                <td>{{ $ctdt->nganh->ten_nganh ?? 'N/A' }}</td>
                                <td>
                                    @if($ctdt->trang_thai === 'published')
                                    <span class="badge bg-success">Đã công bố</span>
                                    @elseif($ctdt->trang_thai === 'approved')
                                    <span class="badge bg-info">Đã duyệt</span>
                                    @elseif($ctdt->trang_thai === 'pending')
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                    @else
                                    <span class="badge bg-secondary">Bản nháp</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-3">Chưa có CTĐT nào sử dụng niên khóa này</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thống kê</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Tổng số CTĐT:</span>
                    <span class="badge bg-primary fs-6">{{ $nienKhoa->chuongTrinhDaoTaos->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Đã công bố:</span>
                    <span class="badge bg-success">{{ $nienKhoa->chuongTrinhDaoTaos->where('trang_thai', 'published')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Chờ duyệt:</span>
                    <span class="badge bg-warning">{{ $nienKhoa->chuongTrinhDaoTaos->where('trang_thai', 'pending')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Bản nháp:</span>
                    <span class="badge bg-secondary">{{ $nienKhoa->chuongTrinhDaoTaos->where('trang_thai', 'draft')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
