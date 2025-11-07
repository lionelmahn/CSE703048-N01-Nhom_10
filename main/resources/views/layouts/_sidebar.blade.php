<nav class="sidebar">
    <div class="p-3">
        <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size: 12px;">Quản trị</h6>
        
        <div class="nav-item @if(request()->routeIs('dashboard')) active @endif">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </div>
        
        {{-- Replace @role with @if(auth()->user()->role === 'admin') --}}
        @if(auth()->user()->role === 'admin')
        <hr class="my-2">
        <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size: 12px;">Danh mục</h6>
        
        <div class="nav-item @if(request()->routeIs('khoa.*')) active @endif">
            <a href="{{ route('khoa.index') }}">
                <i class="fas fa-building"></i> Khoa
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('bo-mon.*')) active @endif">
            <a href="{{ route('bo-mon.index') }}">
                <i class="fas fa-sitemap"></i> Bộ môn
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('hoc-phan.*')) active @endif">
            <a href="{{ route('hoc-phan.index') }}">
                <i class="fas fa-book"></i> Học phần
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('he-dao-tao.*')) active @endif">
            <a href="{{ route('he-dao-tao.index') }}">
                <i class="fas fa-layer-group"></i> Hệ đào tạo
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('nganh.*')) active @endif">
            <a href="{{ route('nganh.index') }}">
                <i class="fas fa-network-wired"></i> Ngành
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('chuyen-nganh.*')) active @endif">
            <a href="{{ route('chuyen-nganh.index') }}">
                <i class="fas fa-code-branch"></i> Chuyên ngành
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('nien-khoa.*')) active @endif">
            <a href="{{ route('nien-khoa.index') }}">
                <i class="fas fa-calendar-alt"></i> Niên khóa
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('khoa-hoc.*')) active @endif">
            <a href="{{ route('khoa-hoc.index') }}">
                <i class="fas fa-graduation-cap"></i> Khóa học
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('khoi-kien-thuc.*')) active @endif">
            <a href="{{ route('khoi-kien-thuc.index') }}">
                <i class="fas fa-cubes"></i> Khối kiến thức
            </a>
        </div>
        
        {{-- Fix route name from user.* to users.* --}}
        <div class="nav-item @if(request()->routeIs('users.*')) active @endif">
            <a href="{{ route('users.index') }}">
                <i class="fas fa-users"></i> Người dùng
            </a>
        </div>
        
        <hr class="my-2">
        <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size: 12px;">Chương trình đào tạo</h6>
        
        <div class="nav-item @if(request()->routeIs('ctdt.*') && !request()->routeIs('ctdt-approval.*')) active @endif">
            <a href="{{ route('ctdt.index') }}">
                <i class="fas fa-list"></i> Danh sách CTĐT
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('ctdt-approval.*')) active @endif">
            <a href="{{ route('ctdt-approval.pending') }}">
                <i class="fas fa-check-circle"></i> Phê duyệt
            </a>
        </div>
        @endif
        
        {{-- Replace @role('khoa') with @if(auth()->user()->role === 'khoa') --}}
        @if(auth()->user()->role === 'khoa')
        <hr class="my-2">
        <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size: 12px;">Khoa của tôi</h6>
        
        <div class="nav-item @if(request()->routeIs('hoc-phan.*')) active @endif">
            <a href="{{ route('hoc-phan.index') }}">
                <i class="fas fa-book"></i> Học phần
            </a>
        </div>
        
        <div class="nav-item @if(request()->routeIs('ctdt.*')) active @endif">
            <a href="{{ route('ctdt.index') }}">
                <i class="fas fa-list"></i> Chương trình đào tạo
            </a>
        </div>
        @endif
        
        <hr class="my-2">
        <h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size: 12px;">Công cộng</h6>
        
        <div class="nav-item @if(request()->routeIs('ctdt-public.*')) active @endif">
            <a href="{{ route('ctdt-public.index') }}">
                <i class="fas fa-eye"></i> CTĐT công khai
            </a>
        </div>
    </div>
</nav>
