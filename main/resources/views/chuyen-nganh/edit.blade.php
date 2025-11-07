@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Chỉnh sửa Chuyên ngành</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('chuyen-nganh.update', $chuyenNganh) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Changed field names to match database schema: ma, ten --}}
                <div class="mb-3">
                    <label class="form-label">Mã Chuyên ngành <span class="text-danger">*</span></label>
                    <input type="text" name="ma" class="form-control @error('ma') is-invalid @enderror" value="{{ old('ma', $chuyenNganh->ma) }}" required>
                    @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tên Chuyên ngành <span class="text-danger">*</span></label>
                    <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror" value="{{ old('ten', $chuyenNganh->ten) }}" required>
                    @error('ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Ngành <span class="text-danger">*</span></label>
                    <select name="nganh_id" class="form-select @error('nganh_id') is-invalid @enderror" required>
                        <option value="">-- Chọn Ngành --</option>
                        @foreach($nganhs as $nganh)
                            {{-- Fixed nganh field name --}}
                            <option value="{{ $nganh->id }}" {{ old('nganh_id', $chuyenNganh->nganh_id) == $nganh->id ? 'selected' : '' }}>
                                {{ $nganh->ten }}
                            </option>
                        @endforeach
                    </select>
                    @error('nganh_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('chuyen-nganh.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
