<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // submitted → cho_phe_duyet, approved → da_phe_duyet, rejected → can_chinh_sua
        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'draft' WHERE trang_thai IN ('pending', 'rejected')");
        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'published' WHERE trang_thai = 'approved'");

        DB::statement("ALTER TABLE chuong_trinh_dao_tao MODIFY COLUMN trang_thai VARCHAR(50) DEFAULT 'draft'");

        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'cho_phe_duyet' WHERE trang_thai = 'submitted'");
        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'da_phe_duyet' WHERE trang_thai IN ('approved', 'published')");
        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'can_chinh_sua' WHERE trang_thai IN ('rejected', 'pending')");

        DB::statement("ALTER TABLE chuong_trinh_dao_tao MODIFY COLUMN trang_thai ENUM('draft', 'can_chinh_sua', 'cho_phe_duyet', 'da_phe_duyet', 'published', 'archived') DEFAULT 'draft'");

        // Add columns for approval workflow
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            if (!Schema::hasColumn('chuong_trinh_dao_tao', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('chuong_trinh_dao_tao', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('chuong_trinh_dao_tao', 'ghi_chu_phe_duyet')) {
                $table->text('ghi_chu_phe_duyet')->nullable()->after('ly_do_tra_ve');
            }
        });

        // Add foreign key if not exists
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_NAME = 'chuong_trinh_dao_tao' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = 'chuong_trinh_dao_tao_approved_by_foreign'");
        if (empty($foreignKeys)) {
            Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
                $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('chuong_trinh_dao_tao', function (Blueprint $table) {
            if (Schema::hasColumn('chuong_trinh_dao_tao', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn(['approved_by', 'approved_at', 'ghi_chu_phe_duyet']);
            }
        });

        // Revert status values
        DB::statement("ALTER TABLE chuong_trinh_dao_tao MODIFY COLUMN trang_thai VARCHAR(50) DEFAULT 'draft'");
        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'submitted' WHERE trang_thai = 'cho_phe_duyet'");
        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'approved' WHERE trang_thai = 'da_phe_duyet'");
        DB::statement("UPDATE chuong_trinh_dao_tao SET trang_thai = 'rejected' WHERE trang_thai = 'can_chinh_sua'");
        DB::statement("ALTER TABLE chuong_trinh_dao_tao MODIFY COLUMN trang_thai ENUM('draft', 'pending', 'approved', 'rejected', 'published', 'archived') DEFAULT 'draft'");
    }
};
