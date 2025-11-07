@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Chi tiết Khoa</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('khoa.index') }}">Khoa</a></li>
                            <li class="breadcrumb-item active">{{ $khoa->ten }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @can('update', $khoa)
                        <a href="{{ route('khoa.edit', $khoa) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Chỉnh sửa
                        </a>
                    @endcan
                    <a href="{{ route('khoa.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Mã khoa</th>
                                <td><strong>{{ $khoa->ma }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tên khoa</th>
                                <td>{{ $khoa->ten }}</td>
                            </tr>
                            <tr>
                                <th>Mô tả</th>
                                <td>{{ $khoa->mo_ta ?? 'Không có mô tả' }}</td>
                            </tr>
                            <tr>
                                <th>Người phụ trách</th>
                                <td>
                                    @if($khoa->nguoiPhuTrach)
                                        {{ $khoa->nguoiPhuTrach->name }}
                                    @else
                                        <span class="text-muted">Chưa có người phụ trách</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo</th>
                                <td>{{ $khoa->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật lần cuối</th>
                                <td>{{ $khoa->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách Bộ môn ({{ $khoa->boMons->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($khoa->boMons->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã bộ môn</th>
                                        <th>Tên bộ môn</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($khoa->boMons as $boMon)
                                        <tr>
                                            <td><strong>{{ $boMon->ma }}</strong></td>
                                            <td>{{ $boMon->ten }}</td>
                                            <td>
                                                <a href="{{ route('bo-mon.show', $boMon) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Chưa có bộ môn nào thuộc khoa này.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống kê</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-0 text-muted">Số bộ môn</h6>
                        </div>
                        <div>
                            <h4 class="mb-0 text-primary">{{ $khoa->boMons->count() }}</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-0 text-muted">Số CTĐT</h6>
                        </div>
                        <div>
                            <h4 class="mb-0 text-success">{{ $khoa->chuongTrinhDaoTaos->count() }}</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 text-muted">Số học phần</h6>
                        </div>
                        <div>
                            <h4 class="mb-0 text-info">{{ $khoa->hocPhans->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            @can('delete', $khoa)
                <div class="card mt-3 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Vùng nguy hiểm</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            Xóa khoa này sẽ xóa toàn bộ bộ môn và dữ liệu liên quan. Hành động này không thể hoàn tác.
                        </p>
                        <form action="{{ route('khoa.destroy', $khoa) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khoa này? Hành động này không thể hoàn tác!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> Xóa khoa
                            </button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@endsection
