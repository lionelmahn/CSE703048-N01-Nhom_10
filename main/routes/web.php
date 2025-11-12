<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CtdtController;
use App\Http\Controllers\CtdtApprovalController;
use App\Http\Controllers\CtdtItemController;
use App\Http\Controllers\HocPhanController;
use App\Http\Controllers\KhoaController;
use App\Http\Controllers\BoMonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HeDaoTaoController;
use App\Http\Controllers\NganhController;
use App\Http\Controllers\NienKhoaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChuyenNganhController;
use App\Http\Controllers\KhoaHocController;
use App\Http\Controllers\KhoiKienThucController;
use App\Http\Controllers\BacHocController;
use App\Http\Controllers\LoaiHinhDaoTaoController;
use App\Http\Controllers\CtdtHocPhanController;
use App\Http\Controllers\CtdtRangBuocController;

// Redirect root to dashboard
Route::redirect('/', '/dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CTDT CRUD routes
    Route::resource('ctdt', CtdtController::class);
    Route::post('/ctdt/generate-code', [CtdtController::class, 'generateCode'])->name('ctdt.generate-code');
    Route::post('/ctdt/{ctdt}/clone', [CtdtController::class, 'clone'])->name('ctdt.clone');
    Route::post('/ctdt/{ctdt}/send-for-approval', [CtdtController::class, 'sendForApproval'])
        ->name('ctdt.send-for-approval');

    // CTDT Approval routes (Admin only)
    Route::middleware('role:admin')->prefix('ctdt-approval')->name('ctdt-approval.')->group(function () {
        Route::get('/pending', [CtdtApprovalController::class, 'pending'])->name('pending');
        Route::post('/{ctdt}/approve', [CtdtApprovalController::class, 'approve'])->name('approve');
        Route::post('/{ctdt}/reject', [CtdtApprovalController::class, 'reject'])->name('reject');
    });

    // CTDT Items routes (add/remove học phần, update order)
    Route::prefix('ctdt/{ctdt}')->name('ctdt-item.')->group(function () {
        Route::post('/add-hoc-phan', [CtdtItemController::class, 'addHocPhan'])->name('add-hoc-phan');
        Route::delete('/hoc-phan/{hocPhan}', [CtdtItemController::class, 'removeHocPhan'])->name('remove-hoc-phan');
        Route::post('/update-order', [CtdtItemController::class, 'updateOrder'])->name('update-order');
    });

    // CTDT Hoc Phan Management routes
    Route::prefix('ctdt/{ctdt}')->name('ctdt.')->group(function () {
        Route::get('/khoi/{khoiId}/hoc-phans', [CtdtHocPhanController::class, 'getHocPhansByKhoi'])
            ->name('khoi.hoc-phans');
        Route::get('/available-hoc-phans', [CtdtHocPhanController::class, 'getAvailableHocPhans'])
            ->name('available-hoc-phans');
        Route::post('/hoc-phans', [CtdtHocPhanController::class, 'store'])
            ->name('hoc-phans.store');
        Route::delete('/hoc-phans/{hocPhan}', [CtdtHocPhanController::class, 'destroy'])
            ->name('hoc-phans.destroy');

        Route::get('/manage-hoc-phan', [CtdtHocPhanController::class, 'manage'])
            ->name('manage-hoc-phan');
        Route::get('/structure', [CtdtHocPhanController::class, 'getCtdtStructure'])
            ->name('structure');
        Route::post('/save-changes', [CtdtHocPhanController::class, 'saveChanges'])
            ->name('save-changes');

        // Rang Buoc routes
        Route::get('/rang-buoc', [CtdtRangBuocController::class, 'index'])->name('rang-buoc');
        Route::get('/rang-buoc/{hocPhanId}', [CtdtRangBuocController::class, 'getRangBuoc'])->name('rang-buoc.get');
        Route::post('/rang-buoc/save', [CtdtRangBuocController::class, 'saveChanges'])->name('rang-buoc.save');
    });

    // Học phần routes (all roles)
    Route::resource('hoc-phan', HocPhanController::class);

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('khoa', KhoaController::class);
        Route::resource('bo-mon', BoMonController::class);
        Route::resource('users', UserController::class);
        Route::resource('he-dao-tao', HeDaoTaoController::class);
        Route::resource('nganh', NganhController::class);
        Route::resource('nien-khoa', NienKhoaController::class);
        Route::resource('chuyen-nganh', ChuyenNganhController::class);
        Route::resource('khoa-hoc', KhoaHocController::class);
        Route::resource('khoi-kien-thuc', KhoiKienThucController::class);
        Route::resource('bac-hoc', BacHocController::class);
        Route::resource('loai-hinh-dao-tao', LoaiHinhDaoTaoController::class);
    });

    // API routes for searching hoc phan
    Route::get('/api/hoc-phan/search', [CtdtHocPhanController::class, 'searchHocPhan'])
        ->name('api.hoc-phan.search');

    Route::get('/api/ctdt/{ctdtId}/khoi-kien-thuc/available', [CtdtHocPhanController::class, 'getAvailableKhoiKienThuc'])
        ->name('api.khoi-kien-thuc.available');
});

// Public routes for viewing published CTDT (no auth required)
Route::get('/ctdt-public', function () {
    $ctdts = \App\Models\ChuongTrinhDaoTao::where('trang_thai', 'published')
        ->with(['khoa', 'nganh', 'heDaoTao'])
        ->orderBy('updated_at', 'desc')
        ->paginate(15);

    return view('ctdt.public-index', compact('ctdts'));
})->name('ctdt-public.index');

// Public route for viewing published CTDT
Route::get('/ctdt/public/{ctdt}', function ($id) {
    $ctdt = \App\Models\ChuongTrinhDaoTao::findOrFail($id);

    if ($ctdt->trang_thai !== 'published') {
        abort(403, 'CTĐT này chưa được công bố');
    }

    $ctdt->load([
        'khoi' => function ($query) {
            $query->orderBy('thu_tu');
        },
        'hocPhans' => function ($query) {
            $query->orderBy('ctdt_hoc_phan.thu_tu');
        }
    ]);

    return view('ctdt.public', compact('ctdt'));
})->name('ctdt-public.show');

// Include Breeze auth routes (keep existing Tailwind auth)
require __DIR__ . '/auth.php';
