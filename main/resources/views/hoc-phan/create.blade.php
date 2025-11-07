@extends('layouts.app')

@section('title', 'Tạo Học phần')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h1 class="h3 mb-4">Tạo Học phần</h1>
        
        <div class="card">
            <div class="card-body">
                <form action="{{ route('hoc-phan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="ma_hp" class="form-label">Mã HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ma_hp') is-invalid @enderror" id="ma_hp" name="ma_hp" value="{{ old('ma_hp') }}" required>
                        @error('ma_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ten_hp" class="form-label">Tên HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten_hp') is-invalid @enderror" id="ten_hp" name="ten_hp" value="{{ old('ten_hp') }}" required>
                        @error('ten_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="so_tinchi" class="form-label">Số tín chỉ <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('so_tinchi') is-invalid @enderror" id="so_tinchi" name="so_tinchi" value="{{ old('so_tinchi') }}" min="1" max="12" required>
                        @error('so_tinchi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="khoa_id" class="form-label">Khoa <span class="text-danger">*</span></label>
                        <select class="form-select @error('khoa_id') is-invalid @enderror" id="khoa_id" name="khoa_id" required>
                            <option value="">-- Chọn khoa --</option>
                            @foreach ($khoas as $khoa)
                            <option value="{{ $khoa->id }}" @selected(old('khoa_id') == $khoa->id)>{{ $khoa->ten }}</option>
                            @endforeach
                        </select>
                        @error('khoa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" @checked(old('active', true))>
                            <label class="form-check-label" for="active">Hoạt động</label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Tạo
                        </button>
                        <a href="{{ route('hoc-phan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
