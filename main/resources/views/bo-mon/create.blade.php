@extends('layouts.app')

@section('title', 'Tạo Bộ môn mới')

@section('content')
<div class="mb-4">
    <h1 class="h3">Tạo Bộ môn mới</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bo-mon.index') }}">Bộ môn</a></li>
            <li class="breadcrumb-item active">Tạo mới</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('bo-mon.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="ma" class="form-label">Mã Bộ môn <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('ma') is-invalid @enderror" 
                    id="ma" name="ma" value="{{ old('ma') }}" required>
                @error('ma')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ten" class="form-label">Tên Bộ môn <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('ten') is-invalid @enderror" 
                    id="ten" name="ten" value="{{ old('ten') }}" required>
                @error('ten')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="khoa_id" class="form-label">Khoa <span class="text-danger">*</span></label>
                <select class="form-select @error('khoa_id') is-invalid @enderror" 
                    id="khoa_id" name="khoa_id" required>
                    <option value="">-- Chọn Khoa --</option>
                    @foreach($khoas as $khoa)
                        {{-- Fixed to use correct column name 'ten' instead of 'ten_khoa' --}}
                        <option value="{{ $khoa->id }}" {{ old('khoa_id') == $khoa->id ? 'selected' : '' }}>
                            {{ $khoa->ma }} - {{ $khoa->ten }}
                        </option>
                    @endforeach
                </select>
                @error('khoa_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="mo_ta" class="form-label">Mô tả</label>
                <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                    id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta') }}</textarea>
                @error('mo_ta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Tạo Bộ môn
                </button>
                <a href="{{ route('bo-mon.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
