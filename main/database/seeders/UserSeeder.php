<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user (Phòng đào tạo)
        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Khoa users (Cán bộ khoa)
        User::create([
            'name' => 'Khoa CNTT',
            'email' => 'khoa.cntt@example.com',
            'password' => Hash::make('password'),
            'role' => 'khoa',
            'khoa_id' => 1, // Khoa Công Nghệ Thông Tin
            'active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Khoa Kinh Tế',
            'email' => 'khoa.kt@example.com',
            'password' => Hash::make('password'),
            'role' => 'khoa',
            'khoa_id' => 2, // Khoa Kinh Tế
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Giảng viên users
        User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'gv1@example.com',
            'password' => Hash::make('password'),
            'role' => 'giang_vien',
            'khoa_id' => 1, // Khoa CNTT (Bộ môn Công Nghệ Phần Mềm thuộc Khoa CNTT)
            'active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Trần Thị B',
            'email' => 'gv2@example.com',
            'password' => Hash::make('password'),
            'role' => 'giang_vien',
            'khoa_id' => 1, // Khoa CNTT (Bộ môn Khoa Học Máy Tính thuộc Khoa CNTT)
            'active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Lê Văn C',
            'email' => 'gv3@example.com',
            'password' => Hash::make('password'),
            'role' => 'giang_vien',
            'khoa_id' => 3, // Khoa Kỹ Thuật Điện Tử và Viễn Thông
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Sinh viên users
        User::create([
            'name' => 'Phạm Minh D',
            'email' => 'sv1@example.com',
            'password' => Hash::make('password'),
            'role' => 'sinh_vien',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Hoàng Thị E',
            'email' => 'sv2@example.com',
            'password' => Hash::make('password'),
            'role' => 'sinh_vien',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Võ Văn F',
            'email' => 'sv3@example.com',
            'password' => Hash::make('password'),
            'role' => 'sinh_vien',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User Bị Khóa',
            'email' => 'locked@example.com',
            'password' => Hash::make('password'),
            'role' => 'giang_vien',
            'khoa_id' => 1,
            'active' => false,
            'email_verified_at' => now(),
        ]);
    }
}
