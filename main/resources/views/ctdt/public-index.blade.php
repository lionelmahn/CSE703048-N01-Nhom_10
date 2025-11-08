<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chương trình đào tạo công khai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .ctdt-card { transition: transform 0.2s, box-shadow 0.2s; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .ctdt-card:hover { transform: translateY(-4px); box-shadow: 0 4px 16px rgba(0,0,0,0.15); }
        .badge-published { background-color: #10b981; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('ctdt-public.index') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Chương trình đào tạo công khai
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
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3">
                    <i class="fas fa-list-alt text-primary me-2"></i>
                    Danh sách chương trình đào tạo công khai
                </h2>
                <p class="text-muted">Xem tất cả các chương trình đào tạo đã được công bố</p>
            </div>
        </div>

        @if($ctdts->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Hiện chưa có chương trình đào tạo nào được công bố.
            </div>
        @else
            <div class="row g-4">
                @foreach($ctdts as $ctdt)
                    <div class="col-md-6 col-lg-4">
                        <div class="card ctdt-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">{{ $ctdt->ten }}</h5>
                                    <span class="badge badge-published">
                                        <i class="fas fa-check-circle me-1"></i> Đã công bố
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <!-- Updated to use new relationships -->
                                    <small class="text-muted d-block">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        <strong>Bậc học:</strong> {{ $ctdt->bacHoc->ten ?? 'N/A' }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-university me-1"></i>
                                        <strong>Khoa:</strong> {{ $ctdt->khoa->ten ?? 'N/A' }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-book me-1"></i>
                                        <strong>Ngành:</strong> {{ $ctdt->nganh->ten ?? 'N/A' }}
                                    </small>
                                    @if($ctdt->chuyenNganh)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-tags me-1"></i>
                                        <strong>Chuyên ngành:</strong> {{ $ctdt->chuyenNganh->ten }}
                                    </small>
                                    @endif
                                    <small class="text-muted d-block">
                                        <i class="fas fa-layer-group me-1"></i>
                                        <strong>Hệ:</strong> {{ $ctdt->heDaoTao->ten ?? 'N/A' }}
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Cập nhật: {{ $ctdt->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>

                                <a href="{{ route('ctdt-public.show', $ctdt->id) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $ctdts->links() }}
            </div>
        @endif
    </div>

    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center text-muted">
            <small>&copy; {{ date('Y') }} Hệ thống quản lý chương trình đào tạo</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
