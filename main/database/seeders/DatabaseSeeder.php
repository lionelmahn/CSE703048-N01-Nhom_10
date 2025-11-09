<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->command->info('🗑️  Clearing existing data...');

        DB::table('ctdt_tuong_duong')->truncate();
        DB::table('ctdt_rang_buoc')->truncate();
        DB::table('ctdt_hoc_phan')->truncate();
        DB::table('ctdt_khoi')->truncate();
        DB::table('chuong_trinh_dao_tao')->truncate();
        DB::table('khoa_hoc')->truncate();
        DB::table('chuyen_nganh')->truncate();
        DB::table('hoc_phan')->truncate();
        DB::table('nganh')->truncate();
        DB::table('bo_mon')->truncate();
        DB::table('khoi_kien_thuc')->truncate();
        DB::table('nien_khoa')->truncate();
        DB::table('he_dao_tao')->truncate();
        DB::table('loai_hinh_dao_tao')->truncate();
        DB::table('bac_hoc')->truncate();
        DB::table('khoa')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->command->info('✨ Seeding fresh data...');

        $this->call([
            // Step 1: Independent tables (no foreign keys)
            BacHocSeeder::class,
            LoaiHinhDaoTaoSeeder::class,
            HeDaoTaoSeeder::class,
            NienKhoaSeeder::class,
            KhoiKienThucSeeder::class,
            KhoaSeeder::class,

            // Step 2: Tables depending on Khoa
            BoMonSeeder::class,
            NganhSeeder::class,

            // Step 3: Users (depends on Khoa and BoMon)
            UserSeeder::class,

            // Step 4: Tables depending on previous entities
            HocPhanSeeder::class,
            ChuyenNganhSeeder::class,
            KhoaHocSeeder::class,

            // Step 5: CTDT and related tables (depend on many entities)
            ChuongTrinhDaoTaoSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Database seeded successfully with sample data!');
        $this->command->info('');
        $this->command->info('📧 Login credentials (password: "password"):');
        $this->command->info('   - Admin: admin@example.com');
        $this->command->info('   - Khoa CNTT: khoa.cntt@example.com');
        $this->command->info('   - Khoa KT: khoa.kt@example.com');
        $this->command->info('   - Giảng viên 1: gv1@example.com');
        $this->command->info('   - Giảng viên 2: gv2@example.com');
        $this->command->info('   - Giảng viên 3: gv3@example.com');
        $this->command->info('   - Sinh viên 1: sv1@example.com');
        $this->command->info('   - Sinh viên 2: sv2@example.com');
        $this->command->info('   - Sinh viên 3: sv3@example.com');
    }
}
