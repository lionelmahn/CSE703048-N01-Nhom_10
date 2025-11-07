<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ctdt->ten_ctdt }} - Chi tiết CTĐT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('ctdt-public.index') }}">
                <i class="fas fa-arrow-left me-2"></i>
                Quay lại danh sách
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-home me-1"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                </a>
            @endauth
        </div>
    </nav>

    <div class="container">
        <div class="card info-card mb-4">
            <div class="card-body">
                <h2 class="card-title mb-3">
                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                    {{ $ctdt->ten_ctdt }}
                </h2>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong><i class="fas fa-university me-2"></i>Khoa:</strong>
                        {{ $ctdt->khoa->ten_khoa }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="fas fa-book me-2"></i>Ngành:</strong>
                        {{ $ctdt->nganh->ten_nganh }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="fas fa-layer-group me-2"></i>Hệ đào tạo:</strong>
                        {{ $ctdt->heDaoTao->ten_he }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="fas fa-calendar me-2"></i>Niên khóa:</strong>
                        {{ $ctdt->nienKhoa->nam_bat_dau }} - {{ $ctdt->nienKhoa->nam_ket_thuc }}
                    </div>
                </div>

                @if($ctdt->mo_ta)
                    <div class="mt-3">
                        <strong><i class="fas fa-info-circle me-2"></i>Mô tả:</strong>
                        <p class="mb-0">{{ $ctdt->mo_ta }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card info-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Danh sách học phần
                </h5>
            </div>
            <div class="card-body">
                @if($ctdt->hocPhans->isEmpty())
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Chưa có học phần nào trong chương trình đào tạo này.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã HP</th>
                                    <th>Tên học phần</th>
                                    <th>Tín chỉ</th>
                                    <th>Khối kiến thức</th>
                                    <th>Loại HP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ctdt->hocPhans as $index => $hocPhan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $hocPhan->ma_hoc_phan }}</strong></td>
                                        <td>{{ $hocPhan->ten_hoc_phan }}</td>
                                        <td>{{ $hocPhan->so_tin_chi }}</td>
                                        <td>{{ $hocPhan->khoiKienThuc->ten_khoi ?? 'N/A' }}</td>
                                        <td>
                                            @if($hocPhan->loai_hoc_phan === 'bat_buoc')
                                                <span class="badge bg-danger">Bắt buộc</span>
                                            @else
                                                <span class="badge bg-info">Tự chọn</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <strong>Tổng số tín chỉ:</strong> {{ $ctdt->hocPhans->sum('so_tin_chi') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center text-muted">
            <small>&copy; {{ date('Y') }} Hệ thống quản lý chương trình đào tạo</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
