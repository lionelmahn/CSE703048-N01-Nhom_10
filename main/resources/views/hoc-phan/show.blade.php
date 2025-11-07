@extends('layouts.app')

@section('title', 'Chi tiết Học phần')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết Học phần</h1>
        <div>
            <a href="{{ route('hoc-phan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            @can('update', $hocPhan)
                <a href="{{ route('hoc-phan.edit', $hocPhan) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Chỉnh sửa
                </a>
            @endcan
            @can('delete', $hocPhan)
                <form action="{{ route('hoc-phan.destroy', $hocPhan) }}" method="POST" class="d-inline" 
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa học phần này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="200">Mã học phần:</th>
                                <td><strong class="text-primary">{{ $hocPhan->ma_hp }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tên học phần:</th>
                                <td><strong>{{ $hocPhan->ten_hp }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tên tiếng Anh:</th>
                                <td>{{ $hocPhan->ten_tieng_anh ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Số tín chỉ:</th>
                                <td><span class="badge bg-info">{{ $hocPhan->so_tinchi }} tín chỉ</span></td>
                            </tr>
                            <tr>
                                <th>Số tiết:</th>
                                <td>
                                    <span class="badge bg-secondary">LT: {{ $hocPhan->so_tiet_ly_thuyet ?? 0 }}</span>
                                    <span class="badge bg-success">TH: {{ $hocPhan->so_tiet_thuc_hanh ?? 0 }}</span>
                                    <span class="badge bg-warning">TL: {{ $hocPhan->so_tiet_tu_hoc ?? 0 }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Khoa quản lý:</th>
                                <td>
                                    @if($hocPhan->khoa)
                                        <a href="{{ route('khoa.show', $hocPhan->khoa) }}" class="text-decoration-none">
                                            {{ $hocPhan->khoa->ten }}
                                        </a>
                                    @else
                                        <span class="text-muted">Chưa xác định</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Bộ môn:</th>
                                <td>
                                    @if($hocPhan->boMon)
                                        {{ $hocPhan->boMon->ten }}
                                    @else
                                        <span class="text-muted">Chưa xác định</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if($hocPhan->mo_ta)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mô tả học phần</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $hocPhan->mo_ta }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khác</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Trạng thái:</strong><br>
                        @if($hocPhan->active)
                            <span class="badge bg-success">Đang hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Ngưng hoạt động</span>
                        @endif
                    </p>
                    <hr>
                    <p class="mb-2">
                        <strong>Ngày tạo:</strong><br>
                        <small class="text-muted">{{ $hocPhan->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-0">
                        <strong>Cập nhật lần cuối:</strong><br>
                        <small class="text-muted">{{ $hocPhan->updated_at->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Số CTĐT sử dụng:</strong><br>
                        <span class="h4 mb-0">{{ $hocPhan->ctdtHocPhans->count() }}</span>
                    </p>
                    @if($hocPhan->ctdtHocPhans->count() > 0)
                        <hr>
                        <small class="text-muted">
                            Học phần này đang được sử dụng trong {{ $hocPhan->ctdtHocPhans->count() }} chương trình đào tạo
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
