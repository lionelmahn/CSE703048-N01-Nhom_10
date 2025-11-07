@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chi Tiết Hệ Đào Tạo</h1>
        <div>
            @can('update', $heDaoTao)
                <a href="{{ route('he-dao-tao.edit', $heDaoTao) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Chỉnh Sửa
                </a>
            @endcan
            <a href="{{ route('he-dao-tao.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay Lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông Tin Cơ Bản</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Mã:</strong></div>
                        <div class="col-md-9">{{ $heDaoTao->ma }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Tên:</strong></div>
                        <div class="col-md-9">{{ $heDaoTao->ten }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Ngày tạo:</strong></div>
                        <div class="col-md-9">{{ $heDaoTao->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Cập nhật:</strong></div>
                        <div class="col-md-9">{{ $heDaoTao->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Danh Sách Ngành ({{ $heDaoTao->nganhs->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($heDaoTao->nganhs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mã Ngành</th>
                                        <th>Tên Ngành</th>
                                        <th>Khoa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($heDaoTao->nganhs as $nganh)
                                        <tr>
                                            <td><strong>{{ $nganh->ma }}</strong></td>
                                            <td>{{ $nganh->ten }}</td>
                                            <td>{{ $nganh->khoa->ten ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Chưa có ngành nào thuộc hệ đào tạo này</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Thống Kê</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Tổng số ngành:</span>
                        <span class="badge bg-primary fs-6">{{ $heDaoTao->nganhs->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
