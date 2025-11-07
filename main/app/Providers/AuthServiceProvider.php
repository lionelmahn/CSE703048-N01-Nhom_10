<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\ChuongTrinhDaoTao;
use App\Models\HocPhan;
use App\Models\Khoa;
use App\Models\BoMon;
use App\Models\HeDaoTao;
use App\Models\Nganh;
use App\Models\User;
use App\Models\ChuyenNganh;
use App\Models\KhoaHoc;
use App\Models\KhoiKienThuc;
use App\Models\NienKhoa; // Added NienKhoa model import
use App\Policies\ChuongTrinhDaoTaoPolicy;
use App\Policies\HocPhanPolicy;
use App\Policies\KhoaPolicy;
use App\Policies\BoMonPolicy;
use App\Policies\HeDaoTaoPolicy;
use App\Policies\NganhPolicy;
use App\Policies\UserPolicy;
use App\Policies\ChuyenNganhPolicy;
use App\Policies\KhoaHocPolicy;
use App\Policies\KhoiKienThucPolicy;
use App\Policies\NienKhoaPolicy; // Added NienKhoaPolicy import

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ChuongTrinhDaoTao::class => ChuongTrinhDaoTaoPolicy::class,
        HocPhan::class => HocPhanPolicy::class,
        Khoa::class => KhoaPolicy::class,
        BoMon::class => BoMonPolicy::class,
        HeDaoTao::class => HeDaoTaoPolicy::class,
        Nganh::class => NganhPolicy::class,
        User::class => UserPolicy::class,
        ChuyenNganh::class => ChuyenNganhPolicy::class,
        KhoaHoc::class => KhoaHocPolicy::class,
        KhoiKienThuc::class => KhoiKienThucPolicy::class,
        NienKhoa::class => NienKhoaPolicy::class, // Registered NienKhoaPolicy
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
