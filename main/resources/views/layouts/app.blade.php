<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản lý CTĐT')</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-bg: #f8f9fa;
            --sidebar-width: 250px;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .wrapper {
            display: flex;
            flex: 1;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            position: fixed;
            height: calc(100vh - 56px - 80px);
            top: 56px;
        }
        
        .content-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
            padding: 20px;
            background: #ffffff;
        }
        
        .nav-item {
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .nav-item:hover {
            background: #e9ecef;
            border-left-color: var(--primary-color);
        }
        
        .nav-item.active {
            background: #e7f1ff;
            border-left-color: var(--primary-color);
        }
        
        .nav-item a {
            color: #495057;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            font-size: 14px;
        }
        
        .nav-item.active a {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        footer {
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
        }
        
        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }
        
        .card {
            border: 1px solid #dee2e6;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .badge-status {
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-draft { background: #e2e3e5; color: #383d41; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #cfe2ff; color: #084298; }
        .status-published { background: #d1e7dd; color: #0f5132; }
        .status-archived { background: #f8d7da; color: #842029; }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
            
            .content-wrapper {
                margin-left: 0;
            }
            
            main {
                padding: 15px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Topbar -->
    @include('layouts._topbar')
    
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts._sidebar')
        
        <!-- Main Content -->
        <div class="content-wrapper">
            <main class="container-fluid">
                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Lỗi!</strong>
                        <ul class="mb-0 ms-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
            
            <!-- Footer -->
            @include('layouts._footer')
        </div>
    </div>
    
    <!-- jQuery before Bootstrap since Bootstrap and Select2 depend on it -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS for searchable dropdowns -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    @stack('scripts')
</body>
</html>
