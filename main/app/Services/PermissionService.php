<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Khởi tạo roles & permissions
     */
    public function initializeRolesAndPermissions(): void
    {
        $permissions = [
            'view_ctdt', 'create_ctdt', 'edit_ctdt', 'delete_ctdt',
            'approve_ctdt', 'publish_ctdt',
            'view_hoc_phan', 'create_hoc_phan', 'edit_hoc_phan', 'delete_hoc_phan',
            'view_khoa', 'create_khoa', 'edit_khoa', 'delete_khoa',
            'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Admin - toàn quyền
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Khoa
        $khoa = Role::firstOrCreate(['name' => 'khoa']);
        $khoa->syncPermissions([
            'view_ctdt', 'create_ctdt', 'edit_ctdt', 'delete_ctdt',
            'view_hoc_phan', 'edit_hoc_phan',
        ]);

        // Giảng viên
        $giangVien = Role::firstOrCreate(['name' => 'giang_vien']);
        $giangVien->syncPermissions(['view_ctdt', 'view_hoc_phan']);

        // Sinh viên
        $sinhVien = Role::firstOrCreate(['name' => 'sinh_vien']);
        $sinhVien->syncPermissions(['view_ctdt', 'view_hoc_phan']);
    }
}
