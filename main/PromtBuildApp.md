Bạn là AI kiến trúc sư & lập trình viên Laravel. Hãy tạo **web dashboard quản lý Chương Trình Đào Tạo (CTĐT)** dùng **Laravel + Filament v3** theo đặc tả sau và bàn giao mã nguồn chạy được ngay.

# 0. Công nghệ & chuẩn dự án

-   PHP ^8.2, Laravel ^10, MySQL ^8
-   Admin: **Filament v3** (TALL: Tailwind + Alpine + Livewire)
-   Auth: Laravel Breeze
-   Build: Vite + TailwindCSS
-   Packages:
    -   spatie/laravel-permission (RBAC chi tiết)
    -   bezhansalleh/filament-shield (quản lý permission trong Filament)
    -   spatie/laravel-activitylog (audit log)
    -   maatwebsite/excel (import/export XLSX/CSV)
    -   laravel/scout + tntsearch (tùy chọn: tìm kiếm)
-   Kiến trúc: Controller + Service + Repository; FormRequest; Policy/Gate; Eloquent API Resources; eager-load tránh N+1.

# 1. Tác nhân & phân quyền (4 vai trò)

-   **Phòng Đào tạo (Admin)**: toàn quyền, phê duyệt CTĐT, cấu hình hệ thống.
-   **Khoa**: tạo/sửa/xóa CTĐT của khoa; gửi phê duyệt; quản lý học phần/khối thuộc khoa.
-   **Giảng viên**: xem CTĐT/học phần; tạo đề xuất chỉnh sửa (comment/todo).
-   **Sinh viên**: xem CTĐT áp dụng theo ngành/niên khóa (read-only).
-   **Multi-tenant theo Khoa**: người thuộc khoa chỉ thấy dữ liệu của khoa mình.
-   Dùng **spatie/permission** + **filament-shield** để ánh xạ permission vào Filament (tạo Role: admin, khoa, giang_vien, sinh_vien).

# 2. Nhóm yêu cầu & Use case

## R1 Quản lý truy cập hệ thống

-   **R1.1** Đăng nhập (**UC1**) – Breeze; redirect theo vai trò.
-   **R1.2** Quên mật khẩu (**UC2**) – mail reset.
-   **R1.3** Thay đổi mật khẩu (**UC3**) – trong trang hồ sơ.
    Dashboard sau khi đăng nhập hiển thị widget theo vai trò.

## R2 Quản lý tổ chức

-   **R2.1** Quản lý **Khoa** (**UC4**) – CRUD, mã/ tên/ mô tả, người phụ trách.
-   **R2.2** Quản lý **Bộ môn** (**UC5**) – CRUD, thuộc về Khoa.
-   **R2.3** Quản lý **người dùng & phân quyền** (**UC6**) – CRUD user, gán vai trò (Admin/Khoa/GV/SV), giới hạn theo Khoa.

## R3 Quản lý danh mục học thuật (UC7–UC13)

-   **R3.1** Quản lý **Hệ đào tạo** (**UC7**) – CĐ/ĐH/Sau ĐH…
-   **R3.2** Quản lý **Khối kiến thức** (**UC8**) – CRUD.
-   **R3.3** Quản lý **Ngành đào tạo** (**UC9**).
-   **R3.4** Quản lý **Chuyên ngành** (**UC10**).
-   **R3.5** Quản lý **Học phần** (**UC11**) **Học phần (master list)**- **Lưu ý quan trọng**: Bảng **hoc_phan** là kho học phần **không chứa** “bắt buộc/tự chọn”, “tiên quyết”, “tương đương”. Mọi ràng buộc/thuộc tính này chỉ xác định **trong phạm vi CTĐT** (ở R4).
-   **R3.6** Quản lý **Niên khóa** (**UC12**).
-   **R3.7** Quản lý **Khóa học** (**UC13**) – (khóa tuyển sinh).

## R4 Xây dựng & quản lý CTĐT

### R4.1 Xây dựng CTĐT (UC14.1 → UC14.10)

-   **UC14.1** Tạo mới CTĐT (bản nháp)
-   **UC14.2** Sao chép CTĐT từ **niên khóa trước**
-   **UC14.3** Sửa CTĐT (thông tin chung, hiệu lực)
-   **UC14.4** Xóa CTĐT (điều kiện: chưa published)
-   **UC14.5** Xem chi tiết CTĐT (cấu trúc Khối → Học phần)
-   **UC14.6** Thêm học phần vào CTĐT (multi-select), gán **khối**, **học kỳ**, **loại {bắt_buộc|tự_chọn}**, **kéo–thả sắp xếp**
-   **UC14.7** Loại bỏ học phần khỏi CTĐT
-   **UC14.8** Quản lý ràng buộc học phần (tiên quyết/song hành) **theo CTĐT**
-   **UC14.9** Quản lý học phần tương đương **theo CTĐT**
-   **UC14.10** Gửi phê duyệt CTĐT (draft → pending)

### R4.2 Phê duyệt CTĐT (UC15 – Phòng Đào tạo)

-   Quy trình trạng thái: `draft → pending → approved → published → archived`
-   Ghi **activity log**: gửi duyệt, duyệt, trả về, publish
-   Trả về phải có lý do; tự tạo To-do cho Khoa

# 3. Dashboard (Filament Widgets theo vai trò)

-   **Cards**:
    -   Số CTĐT **chờ duyệt** (Admin)
    -   **Bản nháp của Khoa tôi** (Khoa)
    -   **Phiên bản/CTĐT tạo mới 7 ngày gần đây**
    -   **CTĐT sắp hết hiệu lực**
-   **Bảng To-do**: yêu cầu chỉnh sửa/duyệt gần đây, lọc theo vai trò
-   **Bộ lọc nhanh**: ngành/khoa/hệ/niên khóa/trạng thái

# 4. Mô hình dữ liệu (migrations – bản tối ưu)

## 4.1 Danh mục tổ chức & master

-   `khoa(id, ma, ten, mo_ta)`
-   `bo_mon(id, ma, ten, khoa_id)`
-   `he_dao_tao(id, ma, ten)`
-   `nganh(id, ma, ten, he_dao_tao_id)`
-   `chuyen_nganh(id, ma, ten, nganh_id)`
-   `nien_khoa(id, ma, nam_bat_dau, nam_ket_thuc)`
-   `khoa_hoc(id, ma, nien_khoa_id)`

### **Master học phần (không chứa ràng buộc/loại)**

-   `hoc_phan(id, ma_hp unique, ten_hp, so_tinchi, khoa_id, bo_mon_id nullable, mo_ta, active bool default true)`

## 4.2 CTĐT & phạm vi theo CTĐT

-   `chuong_trinh_dao_tao(id, ma_ctdt unique, ten, khoa_id, nganh_id, chuyen_nganh_id nullable, he_dao_tao_id, nien_khoa_id, trang_thai enum{draft,pending,approved,published,archived}, hieu_luc_tu, hieu_luc_den, mo_ta, created_by)`

-   `khoi_kien_thuc(id, ma_khoi, ten)` _(danh mục dùng chung)_
-   `ctdt_khoi(id, ctdt_id, khoi_id, thu_tu int, ghi_chu)`

### **Gán học phần vào CTĐT (nơi đặt bắt buộc/tự chọn, học kỳ, khối)**

-   `ctdt_hoc_phan(id, ctdt_id, hoc_phan_id, khoi_id nullable, hoc_ky int nullable, loai enum{bat_buoc,tu_chon}, thu_tu int, ghi_chu)`
    -   Unique: `(ctdt_id, hoc_phan_id)`

### **Ràng buộc & tương đương theo CTĐT**

-   `ctdt_rang_buoc(id, ctdt_id, hoc_phan_id, lien_quan_hp_id, kieu enum{tien_quyet,song_hanh}, logic_nhom enum{AND,OR} default 'AND', nhom int nullable, ghi_chu)`
-   `ctdt_tuong_duong(id, ctdt_id, hoc_phan_id, tuong_duong_hp_id, pham_vi enum{toan_phan,mot_phan} default 'toan_phan', ghi_chu)`

### (Tùy chọn) Nhóm tự chọn theo CTĐT

-   `ctdt_nhom_tu_chon(id, ctdt_id, ten_nhom, mo_ta, min_so_hp nullable, min_so_tinchi nullable)`
-   `ctdt_nhom_hp(id, nhom_id, ctdt_hp_id)` _(tham chiếu tới bản ghi `ctdt_hoc_phan`)_

## 4.3 Người dùng, quyền & audit

-   `users(id, name, email unique, password, role enum{admin,khoa,giang_vien,sinh_vien}, khoa_id nullable, ...)`
-   Bảng spatie/permission; bảng activity_log của spatie

## 4.4 Chỉ mục & ràng buộc

-   Index: `hoc_phan(ma_hp)`, `chuong_trinh_dao_tao(ma_ctdt)`, `ctdt_hoc_phan(ctdt_id, khoi_id)`, `ctdt_rang_buoc(ctdt_id)`, `ctdt_tuong_duong(ctdt_id)`
-   FK on delete cascade: khi xoá CTĐT thì xoá các bảng liên quan (ctdt\_\*)

# 5. Filament: cấu trúc Panel/Resources/Pages

-   **Một Filament Panel** `/admin` cho Admin/Khoa/Giảng viên (Sinh viên xem frontend read-only).
-   **Filament Resources**:

    -   `KhoaResource`, `BoMonResource`, `UserResource` (gán role bằng filament-shield)
    -   `HeDaoTaoResource`, `NganhResource`, `ChuyenNganhResource`, `NienKhoaResource`, `KhoaHocResource`
    -   `HocPhanResource` (master list)
    -   `CTDTResource` (Quan trọng) với **Pages tuỳ biến**:
        -   `ListCTDTs`, `CreateCTDT`, `EditCTDT`, `ViewCTDT` (UC14.5)
        -   **Actions**:
            -   `CloneFromPreviousYearAction` (UC14.2)
            -   `SendForApprovalAction` (UC14.10)
            -   `ApproveAction`, `PublishAction` (UC15 – chỉ Admin)
    -   **Relation Managers** cho `CTDTResource`:
        -   `KhoiKienThucRelationManager` (gắn khối vào CTĐT – bảng `ctdt_khoi`)
        -   `HocPhanRelationManager` (gắn học phần vào CTĐT – bảng `ctdt_hoc_phan`, gồm trường `loai`, `hoc_ky`, `thu_tu`, drag-n-drop)
        -   `RangBuocRelationManager` (ctdt_rang_buoc)
        -   `TuongDuongRelationManager` (ctdt_tuong_duong)

-   **Filament Widgets** (Dashboard):
    -   `PendingCtdtCount`, `MyDraftCtdtCount`, `NewCtdtThisWeek`, `ExpiringSoonCtdt`
    -   `RecentActivityLogTable`, `TodoRequestsTable`

# 6. Luồng & ràng buộc nghiệp vụ

-   **UC14.6 thêm học phần**: tạo bản ghi `ctdt_hoc_phan` với `loai`, `hoc_ky`, `khoi_id`, `thu_tu`
-   **UC14.7 loại bỏ**: xoá bản ghi `ctdt_hoc_phan` và các ràng buộc liên quan (nếu có)
-   **UC14.8/14.9**: thao tác trên `ctdt_rang_buoc` & `ctdt_tuong_duong`; validate chống vòng lặp, tự tham chiếu, hoặc thay thế không hợp lệ
-   **UC14.2 clone**: copy toàn bộ `ctdt_khoi`, `ctdt_hoc_phan`, `ctdt_rang_buoc`, `ctdt_tuong_duong` từ CTĐT nguồn sang CTĐT mới (niên khóa đích)
-   **Trạng thái**: chặn sửa/xoá khi `published` (chỉ `archived` hoặc clone version mới); chỉ Admin được `approve/publish`

# 7. Bảo mật & Policy

-   Policies cho mọi Resource; trong Policy, **giới hạn theo khoa** (row-level security)
-   Shield: sinh permission theo resource + action; map role → permission
-   Validation bằng FormRequest; kiểm soát unique theo (ctdt_id, hoc_phan_id)

# 8. Import/Export

-   Import/Export **hoc_phan** và cấu trúc CTĐT (ctdt_hoc_phan) bằng maatwebsite/excel
-   Mẫu file import + hướng dẫn trong README

# 9. Hướng dẫn cài & chạy (ghi trong README)

1. `composer install`
2. sao chép `.env.example` → `.env`, cấu hình MySQL
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `php artisan shield:install` (nếu cần sinh sẵn role/permission)
6. `npm install && npm run build`
7. `php artisan serve`

-   Tài khoản demo:
    -   admin@demo.test / password
    -   khoa@demo.test / password
    -   gv@demo.test / password
    -   sv@demo.test / password

# 10. Kiểm thử & chất lượng

-   Ít nhất 10 tests (Feature/Policy) cho: gửi duyệt, duyệt/publish, giới hạn theo khoa, thêm/xoá học phần trong CTĐT, ràng buộc tiên quyết
-   Pint (code style) & IDE helper; (tuỳ chọn) CI GitHub Actions chạy test

# 11. Tiêu chí hoàn thành (Acceptance)

-   Đăng nhập/đổi mật khẩu ok; vào **/admin** hiện dashboard theo vai trò
-   CRUD danh mục (R2, R3) chạy được; **hoc_phan** là master list
-   Thực hiện được UC14.1–UC14.10 & UC15; trạng thái CTĐT đúng quy trình
-   Dashboard có 4 cards + bảng To-do; hiển thị Activity Log gần nhất
-   Import/Export hoạt động; quyền giới hạn theo khoa

# 12. Yêu cầu bàn giao

-   Mã nguồn hoàn chỉnh, migrations/seeders/policies/resources/pages/widgets
-   Ảnh chụp màn hình các màn hình chính
-   README đầy đủ (cả mẫu file import)
