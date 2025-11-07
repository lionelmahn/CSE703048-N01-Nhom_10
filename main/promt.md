# LỆNH THỰC THI (BREEZE ĐÃ CÀI SẴN)

Hãy bổ sung **toàn bộ phần Dashboard + CRUD + RBAC + nghiệp vụ CTĐT** vào _dự án Laravel hiện có_ (đã cài **Laravel Breeze**). **Không được sửa, thay thế hay xóa phần Auth của Breeze** (UI Tailwind mặc định).  
YÊU CẦU BẮT BUỘC:

-   Auth vẫn dùng **Breeze + Tailwind** như hiện có (không generate lại).
-   **Dashboard/Quản trị dùng Bootstrap 5 viết thủ công** (không template ngoài), nạp **Bootstrap 5 & Chart.js bằng CDN**.
-   **Sinh sẵn 4 file layout**: `resources/views/layouts/app.blade.php`, `layouts/_sidebar.blade.php`, `layouts/_topbar.blade.php`, `layouts/_footer.blade.php`.
-   **Mọi trang con quản trị đều** `@extends('layouts.app')`.
-   **Sau đăng nhập**: tự động **redirect tới `/dashboard`** (D1).

---

## 0. Công nghệ & Kiến trúc

-   Laravel ^10, PHP ^8.2, MySQL ^8
-   **Breeze đã cài sẵn** → giữ nguyên routes/views của Auth (Tailwind)
-   **Admin/Dashboard**: Bootstrap 5 thuần (viết tay), **CDN** (không npm)
-   **Biểu đồ**: Chart.js **CDN**
-   Build: Vite (giữ cấu hình hiện tại cho app), phần Bootstrap/Chart.js load qua CDN trong layout dashboard
-   Packages cần cài thêm:
    -   `spatie/laravel-permission` (RBAC: roles `admin`, `khoa`, `giang_vien`, `sinh_vien`)
    -   `spatie/laravel-activitylog` (audit log)
    -   `maatwebsite/excel` (import/export CSV/XLSX)
-   Kiến trúc code: **Route → Controller → (Service tuỳ chọn) → Model → View (Blade)**, Policy/Gate, FormRequest
-   Tối ưu: eager-load, paginate, validate chuẩn

---

## 1. Tác nhân & Phân quyền

-   **Phòng Đào tạo (Admin)**: toàn quyền, phê duyệt/publish CTĐT, cấu hình hệ thống
-   **Khoa**: CRUD CTĐT của khoa mình; gửi phê duyệt; quản lý danh mục thuộc khoa
-   **Giảng viên**: xem CTĐT/học phần; đề xuất (read-only)
-   **Sinh viên**: xem CTĐT `published` (read-only)
-   **Row-level** theo `khoa_id` trong Policy (user chỉ thấy dữ liệu khoa mình, trừ Admin)

---

## 2. Use case & Yêu cầu chức năng

### R1 Quản lý truy cập (dùng Breeze sẵn có)

-   **Không sửa phần Auth của Breeze**
-   Sau đăng nhập, **redirect tới `/dashboard`**

### R2 Quản lý tổ chức

-   **Khoa** – CRUD
-   **Bộ môn** – CRUD (thuộc Khoa)
-   **Người dùng & phân quyền** – CRUD user, gán role (Admin/Khoa/GV/SV)

### R3 Danh mục học thuật

-   **Hệ đào tạo**, **Khối kiến thức**, **Ngành**, **Chuyên ngành**, **Niên khóa**, **Khóa học** – CRUD
-   **Học phần** – CRUD (Master list, **KHÔNG** lưu BB/TC/tiên quyết/tương đương tại đây)
-   Import/Export học phần (CSV/XLSX)

### R4 Xây dựng & quản lý CTĐT

**R4.1 – UC14.x**
| Mã | Use case |
|---|---|
| UC14.1 | Tạo mới CTĐT (bản nháp) |
| UC14.2 | Sao chép CTĐT từ **niên khóa trước** |
| UC14.3 | Sửa CTĐT |
| UC14.4 | Xoá CTĐT (khi chưa publish) |
| UC14.5 | Xem chi tiết CTĐT (cấu trúc **Khối → Học phần**) |
| UC14.6 | Thêm học phần vào CTĐT (multi-select; gán **khối**, **học kỳ**, **loại**; **có trường `thu_tu` để sắp xếp**) |
| UC14.7 | Loại bỏ học phần khỏi CTĐT |
| UC14.8 | Quản lý ràng buộc học phần (**tiên quyết/song hành**) theo CTĐT |
| UC14.9 | Quản lý học phần **tương đương** theo CTĐT |
| UC14.10 | Gửi phê duyệt CTĐT (`draft → pending`) |

**R4.2 – UC15 (Phòng Đào tạo)**

-   Trạng thái CTĐT: `draft → pending → approved → published → archived`
-   Ghi activity log: gửi duyệt, duyệt, publish, trả về (trả về phải có lý do)

---

## 3. Dashboard (Blade + Bootstrap CDN)

**File:** `resources/views/dashboard/index.blade.php` (kế thừa `layouts/app.blade.php`)  
**Yêu cầu UI:**

-   **Sidebar trái + Topbar + Footer** (Bootstrap), container-fluid, responsive
-   **4 Cards**:
    1. CTĐT **chờ duyệt** (Admin)
    2. **Bản nháp của Khoa tôi** (Khoa)
    3. CTĐT **mới 7 ngày**
    4. CTĐT **sắp hết hiệu lực**
-   **2 biểu đồ** (Chart.js CDN): 1 Area + 1 Bar
-   **Bảng “Hoạt động gần đây”** lấy từ `activity_log` (10 dòng)

---

## 4. Mô hình dữ liệu (migrations)

### 4.1 Danh mục & master

-   `khoa(id, ma, ten, mo_ta)`
-   `bo_mon(id, ma, ten, khoa_id)`
-   `he_dao_tao(id, ma, ten)`
-   `nganh(id, ma, ten, he_dao_tao_id)`
-   `chuyen_nganh(id, ma, ten, nganh_id)`
-   `nien_khoa(id, ma, nam_bat_dau, nam_ket_thuc)`
-   `khoa_hoc(id, ma, nien_khoa_id)`
-   `hoc_phan(id, ma_hp UNIQUE, ten_hp, so_tinchi, khoa_id, bo_mon_id NULL, mo_ta, active BOOL DEFAULT 1)`

### 4.2 CTĐT & thuộc tính theo CTĐT

-   chuong_trinh_dao_tao(
    id, ma_ctdt UNIQUE, ten, khoa_id, nganh_id, chuyen_nganh_id NULL,
    he_dao_tao_id, nien_khoa_id, trang_thai ENUM{draft,pending,approved,published,archived},
    hieu_luc_tu, hieu_luc_den, mo_ta, created_by
    )

-   khoi_kien_thuc(id, ma_khoi, ten) -- danh mục chung
-   ctdt_khoi(id, ctdt_id, khoi_id, thu_tu INT, ghi_chu)

-   ctdt_hoc_phan(
    id, ctdt_id, hoc_phan_id, khoi_id NULL, hoc_ky INT NULL,
    loai ENUM{bat_buoc,tu_chon}, thu_tu INT, ghi_chu
    ) -- UNIQUE (ctdt_id, hoc_phan_id)

-   ctdt_rang_buoc(
    id, ctdt_id, hoc_phan_id, lien_quan_hp_id,
    kieu ENUM{tien_quyet,song_hanh}, logic_nhom ENUM{AND,OR} DEFAULT 'AND',
    nhom INT NULL, ghi_chu
    )

-   ctdt_tuong_duong(
    id, ctdt_id, hoc_phan_id, tuong_duong_hp_id,
    pham_vi ENUM{toan_phan,mot_phan} DEFAULT 'toan_phan', ghi_chu
    )

**Index/FK**: thêm index cột tra cứu; **ON DELETE CASCADE** tất cả bảng `ctdt_*`.

---

## 5. Cấu trúc MVC phải sinh

-   `app/Http/Controllers/`
-   `DashboardController.php`
-   `CtdtController.php`
-   `CtdtApprovalController.php`
-   `CtdtItemController.php`
-   `HocPhanController.php`
-   `KhoaController.php`
-   `BoMonController.php`
-   `HeDaoTaoController.php`
-   `NganhController.php`
-   `ChuyenNganhController.php`
-   `NienKhoaController.php`
-   `KhoaHocController.php`
-   `UserController.php`

-   `app/Policies/.php` -- Policy cho mọi model
-   `app/Http/Requests/.php` -- FormRequest validate

-   `resources/views/layouts/app.blade.php` -- Bootstrap 5 (CDN), wrapper, @yield('content')
-   `resources/views/layouts/\_sidebar.blade.php` -- menu trái (ẩn/hiện bằng @role/@can)
-   `resources/views/layouts/\_topbar.blade.php` -- search + user dropdown
-   `resources/views/layouts/\_footer.blade.php` -- footer

-   `resources/views/dashboard/index.blade.php`
-   `resources/views/ctdt/{index,create,edit,show}.blade.php`
-   `resources/views/hocphan/{index,create,edit,show}.blade.php`
-   ... (các view CRUD còn lại)

**BẮT BUỘC:** Các view quản trị **đều** `@extends('layouts.app')`.

---

## 6. Routes (web.php)

-   **Redirect sau login**: cấu hình/bổ sung để **vào `/dashboard`** (D1)
-   Nhóm `middleware(['auth'])` cho dashboard & CRUD
-   Public: `GET /ctdt/{ctdt}` → **chỉ hiển thị khi `published`**
-   RBAC theo role:
    -   Admin: full
    -   Khoa: CRUD CTĐT của khoa mình; `send-for-approval` khi `draft`
    -   Giảng viên/Sinh viên: read-only phù hợp
-   **Action UC** (POST):
    -   `/ctdt/{id}/clone` (UC14.2)
    -   `/ctdt/{id}/send-for-approval` (UC14.10)
    -   `/ctdt/{id}/approve` & `/ctdt/{id}/publish` (UC15 – Admin)
    -   CRUD `ctdt_hoc_phan` (UC14.6–UC14.7)
    -   resource `ctdt/{id}/rang-buoc` (UC14.8) & `ctdt/{id}/tuong-duong` (UC14.9)

---

## 7. Policy & Middleware

-   Policy cho mọi model; nguyên tắc:
    -   **Admin**: full
    -   **Khoa**: thao tác dữ liệu có `khoa_id` = user.khoa_id; chỉ `update/delete` khi `trang_thai = draft`
    -   **Giảng viên**: read-only (hoặc theo permission chi tiết)
    -   **Sinh viên**: read-only, chỉ CTĐT `published`
-   Đăng ký Policy trong `AuthServiceProvider`
-   Dùng middleware `role:` & `permission:` (spatie) trên nhóm route

---

## 8. Seeder & Tài khoản mẫu

| Email           | Role       | Password |
| --------------- | ---------- | -------- |
| admin@demo.test | admin      | password |
| khoa@demo.test  | khoa       | password |
| gv@demo.test    | giang_vien | password |
| sv@demo.test    | sinh_vien  | password |

Seed thêm: 2 Khoa, 3 Bộ môn, 30 Học phần, 2 CTĐT (1 `draft`, 1 `pending`), vài `activity_log` mẫu.

---

## 9. Hướng dẫn chạy (README)

-   `composer install`
-   `cp .env.example .env`
-   `php artisan key:generate`

-   `cấu hình DB MySQL`

-   `php artisan migrate --seed`
-   `npm install && npm run build`
-   `php artisan serve`

> **Lưu ý:** Bootstrap 5 & Chart.js dùng **CDN** trong `layouts/app.blade.php`.  
> **Không** sửa phần Auth của Breeze (Tailwind), chỉ bổ sung dashboard quản trị.

---

## 10. Tiêu chí nghiệm thu (Acceptance)

-   Đăng nhập → **/dashboard**
-   Layout dashboard đúng: **sidebar trái + topbar + footer**, responsive; **mọi trang con `@extends('layouts.app')`**
-   RBAC/Policy đúng cho 4 vai trò; **giới hạn theo khoa**
-   Thực hiện đủ **UC14.1 → UC14.10** và **UC15**
-   Trang CTĐT chi tiết hiển thị **Khối → Học phần**, có `thu_tu`, thêm/xoá/gán đúng
-   Import/Export chạy được
-   Activity log hiển thị trên dashboard
-   Code chuẩn: FormRequest, paginate, eager-load; **≥ 10 Feature/Policy tests**

---

## 11. Yêu cầu bàn giao

-   Mã nguồn bổ sung hoàn chỉnh (MVC) + migrations/seeders/policies/controllers/views
-   README đầy đủ (ghi rõ Breeze đã có sẵn)
-   Ảnh chụp giao diện chính (dashboard, CRUD CTĐT, xem CTĐT)
-   File import/export mẫu (CSV/XLSX)
