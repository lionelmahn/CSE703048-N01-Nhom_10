<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cards data based on role
        $pendingCount = 0;
        $draftCount = 0;

        if ($user->role === 'admin') {
            $pendingCount = ChuongTrinhDaoTao::where('trang_thai', 'pending')->count();
            $newCount = ChuongTrinhDaoTao::where('created_at', '>=', now()->subDays(7))->count();
        } else {
            $draftCount = ChuongTrinhDaoTao::where('khoa_id', $user->khoa_id)
                ->where('trang_thai', 'draft')->count();
            $newCount = ChuongTrinhDaoTao::where('khoa_id', $user->khoa_id)
                ->where('created_at', '>=', now()->subDays(7))->count();
        }

        $expiringCount = ChuongTrinhDaoTao::where('hieu_luc_den', '<=', now()->addDays(30))
            ->where('hieu_luc_den', '>=', now())
            ->count();

        $recentCtdt = ChuongTrinhDaoTao::latest()->take(10)->get();

        // Chart data
        $ctdtByStatus = ChuongTrinhDaoTao::selectRaw('trang_thai, COUNT(*) as count')
            ->groupBy('trang_thai')
            ->pluck('count', 'trang_thai');

        $ctdtByKhoa = ChuongTrinhDaoTao::selectRaw('khoa_id, COUNT(*) as count')
            ->groupBy('khoa_id')
            ->with('khoa')
            ->get();

        return view('dashboard.index', compact(
            'user',
            'pendingCount',
            'draftCount',
            'newCount',
            'expiringCount',
            'recentCtdt',
            'ctdtByStatus',
            'ctdtByKhoa'
        ));
    }
}
