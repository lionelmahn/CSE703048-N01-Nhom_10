@extends('layouts.app')

@section('title', 'Tạo Khoa')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <h1 class="h3 mb-4">Tạo Khoa</h1>
        
        <div class="card">
            <div class="card-body">
                <form action="{{ route('khoa.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="ma" class="form-label">Mã khoa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ma') is-invalid @enderror" id="ma" name="ma" value="{{ old('ma') }}" required>
                        @error('ma')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ten" class="form-label">Tên khoa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten') is-invalid @enderror" id="ten" name="ten" value="{{ old('ten') }}" required>
                        @error('ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Tạo
                        </button>
                        <a href="{{ route('khoa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
