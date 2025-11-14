@extends('layouts.app')

@section('title', 'Chi tiết Khóa học')

@section('content')
<div class="container">

    <div class="card">
        <h2 style="margin-bottom: 15px;">📘 Thông tin Khóa học</h2>

        <div class="course-detail">
            <p><strong>Mã khóa học:</strong> {{ $khoahoc->MaKhoaHoc }}</p>
            <p><strong>Tên khóa học:</strong> {{ $khoahoc->TenKhoaHoc }}</p>
            <p><strong>Năm bắt đầu:</strong> {{ $khoahoc->NamBatDau }}</p>
            <p><strong>Ghi chú:</strong> {{ $khoahoc->GhiChu ?? 'Không có' }}</p>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('khoahoc.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
            <a href="{{ route('khoahoc.edit', $khoahoc->id) }}" class="btn btn-primary">✏ Sửa</a>
        </div>
    </div>

</div>
@endsection
