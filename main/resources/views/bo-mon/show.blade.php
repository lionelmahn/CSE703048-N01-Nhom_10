@extends('layouts.app')

@section('title', 'Chi tiết Bộ môn')

@section('content')
<div class="mb-4">
    <h1 class="h3">Chi tiết Bộ môn</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bo-mon.index') }}">Bộ môn</a></li>
            <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $boMon->ten_bo_mon }}</h5>
        <div class="btn-group">
            @can('update', $boMon)
                <a href="{{ route('bo-mon.edit', $boMon) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil"></i> Sửa
                </a>
            @endcan
            <a href="{{ route('bo-mon.index') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Mã Bộ môn:</th>
                        <td>{{ $boMon->ma_bo_mon }}</td>
                    </tr>
                    <tr>
                        <th>Tên Bộ môn:</th>
                        <td>{{ $boMon->ten_bo_mon }}</td>
                    </tr>
                    <tr>
                        <th>Khoa:</th>
                        <td>{{ $boMon->khoa->ten_khoa ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Mô tả:</th>
                        <td>{{ $boMon->mo_ta ?? 'Không có mô tả' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $boMon->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối:</th>
                        <td>{{ $boMon->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
