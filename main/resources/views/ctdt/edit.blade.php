@extends('layouts.app')

@section('title', 'Sửa Chương trình Đào tạo')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h1 class="h3 mb-4">Sửa Chương trình Đào tạo</h1>
        
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ctdt.update', $ctdt) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="ma_ctdt" class="form-label">Mã CTĐT <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ma_ctdt') is-invalid @enderror" id="ma_ctdt" name="ma_ctdt" value="{{ old('ma_ctdt', $ctdt->ma_ctdt) }}" readonly>
                        <small class="text-muted">Mã CTĐT được tạo tự động và không thể thay đổi</small>
                        @error('ma_ctdt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ten" class="form-label">Tên CTĐT <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten') is-invalid @enderror" id="ten" name="ten" value="{{ old('ten', $ctdt->ten) }}" required>
                        @error('ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Added Bac Hoc field -->
                    <div class="mb-3">
                        <label for="bac_hoc_id" class="form-label">Bậc học <span class="text-danger">*</span></label>
                        <select class="form-select @error('bac_hoc_id') is-invalid @enderror" id="bac_hoc_id" name="bac_hoc_id" required>
                            <option value="">-- Chọn bậc học --</option>
                            @foreach ($bacHocs as $bacHoc)
                            <option value="{{ $bacHoc->id }}" @selected(old('bac_hoc_id', $ctdt->bac_hoc_id) == $bacHoc->id)>{{ $bacHoc->ten }}</option>
                            @endforeach
                        </select>
                        @error('bac_hoc_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Added Loai Hinh Dao Tao field -->
                    <div class="mb-3">
                        <label for="loai_hinh_dao_tao_id" class="form-label">Loại hình đào tạo <span class="text-danger">*</span></label>
                        <select class="form-select @error('loai_hinh_dao_tao_id') is-invalid @enderror" id="loai_hinh_dao_tao_id" name="loai_hinh_dao_tao_id" required>
                            <option value="">-- Chọn loại hình --</option>
                            @foreach ($loaiHinhDaoTaos as $loaiHinh)
                            <option value="{{ $loaiHinh->id }}" @selected(old('loai_hinh_dao_tao_id', $ctdt->loai_hinh_dao_tao_id) == $loaiHinh->id)>{{ $loaiHinh->ten }}</option>
                            @endforeach
                        </select>
                        @error('loai_hinh_dao_tao_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="khoa_id" class="form-label">Khoa <span class="text-danger">*</span></label>
                        <select class="form-select @error('khoa_id') is-invalid @enderror" id="khoa_id" name="khoa_id" required>
                            <option value="">-- Chọn khoa --</option>
                            @foreach ($khoas as $khoa)
                            <option value="{{ $khoa->id }}" @selected(old('khoa_id', $ctdt->khoa_id) == $khoa->id)>{{ $khoa->ten }}</option>
                            @endforeach
                        </select>
                        @error('khoa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Added Nganh field -->
                    <div class="mb-3">
                        <label for="nganh_id" class="form-label">Ngành <span class="text-danger">*</span></label>
                        <select class="form-select @error('nganh_id') is-invalid @enderror" id="nganh_id" name="nganh_id" required>
                            <option value="">-- Chọn ngành --</option>
                            @foreach ($nganhs as $nganh)
                            <option value="{{ $nganh->id }}" @selected(old('nganh_id', $ctdt->nganh_id) == $nganh->id)>{{ $nganh->ma }} - {{ $nganh->ten }}</option>
                            @endforeach
                        </select>
                        @error('nganh_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Added Chuyen Nganh field (optional) -->
                    <div class="mb-3">
                        <label for="chuyen_nganh_id" class="form-label">Chuyên ngành</label>
                        <select class="form-select @error('chuyen_nganh_id') is-invalid @enderror" id="chuyen_nganh_id" name="chuyen_nganh_id">
                            <option value="">-- Không có (đại trà) --</option>
                            @foreach ($chuyenNganhs as $chuyenNganh)
                            <option value="{{ $chuyenNganh->id }}" @selected(old('chuyen_nganh_id', $ctdt->chuyen_nganh_id) == $chuyenNganh->id)>{{ $chuyenNganh->ma }} - {{ $chuyenNganh->ten }}</option>
                            @endforeach
                        </select>
                        @error('chuyen_nganh_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="he_dao_tao_id" class="form-label">Hệ đào tạo <span class="text-danger">*</span></label>
                        <select class="form-select @error('he_dao_tao_id') is-invalid @enderror" id="he_dao_tao_id" name="he_dao_tao_id" required>
                            <option value="">-- Chọn hệ --</option>
                            @foreach ($heDaoTaos as $he)
                            <option value="{{ $he->id }}" @selected(old('he_dao_tao_id', $ctdt->he_dao_tao_id) == $he->id)>{{ $he->ten }}</option>
                            @endforeach
                        </select>
                        @error('he_dao_tao_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Added Khoa Hoc field -->
                    <div class="mb-3">
                        <label for="khoa_hoc_id" class="form-label">Khóa học <span class="text-danger">*</span></label>
                        <select class="form-select @error('khoa_hoc_id') is-invalid @enderror" id="khoa_hoc_id" name="khoa_hoc_id" required>
                            <option value="">-- Chọn khóa học --</option>
                            @foreach ($khoaHocs as $khoaHoc)
                            <option value="{{ $khoaHoc->id }}" @selected(old('khoa_hoc_id', $ctdt->khoa_hoc_id) == $khoaHoc->id)>{{ $khoaHoc->ma }} ({{ $khoaHoc->nienKhoa->nam_bat_dau }}-{{ $khoaHoc->nienKhoa->nam_ket_thuc }})</option>
                            @endforeach
                        </select>
                        @error('khoa_hoc_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nien_khoa_id" class="form-label">Niên khóa <span class="text-danger">*</span></label>
                        <select class="form-select @error('nien_khoa_id') is-invalid @enderror" id="nien_khoa_id" name="nien_khoa_id" required>
                            <option value="">-- Chọn niên khóa --</option>
                            @foreach ($nienKhoas as $nk)
                            <option value="{{ $nk->id }}" @selected(old('nien_khoa_id', $ctdt->nien_khoa_id) == $nk->id)>{{ $nk->ma }} ({{ $nk->nam_bat_dau }}-{{ $nk->nam_ket_thuc }})</option>
                            @endforeach
                        </select>
                        @error('nien_khoa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="hieu_luc_tu" class="form-label">Hiệu lực từ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('hieu_luc_tu') is-invalid @enderror" id="hieu_luc_tu" name="hieu_luc_tu" value="{{ old('hieu_luc_tu', $ctdt->hieu_luc_tu->format('Y-m-d')) }}" required>
                        @error('hieu_luc_tu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="hieu_luc_den" class="form-label">Hiệu lực đến</label>
                        <input type="date" class="form-control @error('hieu_luc_den') is-invalid @enderror" id="hieu_luc_den" name="hieu_luc_den" value="{{ old('hieu_luc_den', $ctdt->hieu_luc_den?->format('Y-m-d')) }}">
                        @error('hieu_luc_den')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta', $ctdt->mo_ta) }}</textarea>
                        @error('mo_ta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                        <a href="{{ route('ctdt.show', $ctdt) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
