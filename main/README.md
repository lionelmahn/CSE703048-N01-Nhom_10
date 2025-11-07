# Hệ Thống Quản Lý Chương Trình Đào Tạo (CTĐT)

Hệ thống web quản lý chương trình đào tạo dành cho các trường đại học, viện đào tạo. Xây dựng bằng Laravel MVC thuần với Bootstrap 5 dashboard.

## Tính Năng Chính

-   **Quản lý tổ chức**: Khoa, Bộ môn, Hệ đào tạo, Ngành, Chuyên ngành
-   **Quản lý danh mục**: Học phần, Niên khóa, Khối kiến thức
-   **Xây dựng CTĐT**: Tạo, chỉnh sửa, sao chép, gửi phê duyệt chương trình
-   **Phân quyền RBAC**: 4 vai trò (Admin, Khoa, Giảng viên, Sinh viên)
-   **Giới hạn dữ liệu**: Khoa chỉ thấy dữ liệu của khoa mình (Row-level security)
-   **Activity Log**: Ghi lại tất cả hoạt động của người dùng
-   **Dashboard**: Thống kê, biểu đồ, hoạt động gần đây

## Công Nghệ

-   **Laravel 10** với PHP 8.2+
-   **MySQL 8.0+** (hoặc MariaDB)
-   **Breeze**: Authentication (Tailwind CSS)
-   **Bootstrap 5**: Dashboard UI (CDN)
-   **Chart.js**: Biểu đồ (CDN)
-   **Spatie Permission**: RBAC
-   **Spatie Activity Log**: Audit trail
-   **Maatwebsite Excel**: Import/Export

## Cài Đặt

### 1. Clone hoặc tải mã nguồn

\`\`\`bash
git clone <repository-url> ctdt-management
cd ctdt-management
\`\`\`

### 2. Cài đặt Composer dependencies

\`\`\`bash
composer install
\`\`\`

### 3. Cấu hình môi trường

\`\`\`bash
cp .env.example .env
php artisan key:generate
\`\`\`

### 4. Cấu hình database trong `.env`

\`\`\`
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ctdt_management
DB_USERNAME=root
DB_PASSWORD=
\`\`\`

### 5. Chạy migrations và seeders

\`\`\`bash
php artisan migrate --seed
\`\`\`

### 6. Cài đặt npm dependencies (cho Breeze auth UI)

\`\`\`bash
npm install && npm run build
\`\`\`

### 7. Khởi động server

\`\`\`bash
php artisan serve
\`\`\`

Truy cập: **http://localhost:8000**

## Tài Khoản Demo

| Email           | Role       | Password | Ghi chú            |
| --------------- | ---------- | -------- | ------------------ |
| admin@demo.test | admin      | password | Toàn quyền         |
| khoa@demo.test  | khoa       | password | Quản trị khoa CNTT |
| gv@demo.test    | giang_vien | password | Giảng viên         |
| sv@demo.test    | sinh_vien  | password | Sinh viên          |

## Cấu Trúc MVC

\`\`\`
app/
├── Http/
│ ├── Controllers/
│ │ ├── DashboardController.php
│ │ ├── CtdtController.php
│ │ ├── CtdtApprovalController.php
│ │ ├── CtdtItemController.php
│ │ ├── HocPhanController.php
│ │ ├── KhoaController.php
│ │ ├── BoMonController.php
│ │ ├── UserController.php
│ │ ├── HeDaoTaoController.php
│ │ ├── NganhController.php
│ │ └── NienKhoaController.php
│ ├── Requests/
│ │ ├── StoreCTDTRequest.php
│ │ ├── UpdateCTDTRequest.php
│ │ ├── StoreHocPhanRequest.php
│ │ └── ...
│ └── Middleware/
│
├── Models/
│ ├── ChuongTrinhDaoTao.php
│ ├── HocPhan.php
│ ├── Khoa.php
│ ├── BoMon.php
│ ├── User.php
│ └── ...
│
├── Policies/
│ ├── ChuongTrinhDaoTaoPolicy.php
│ ├── HocPhanPolicy.php
│ ├── KhoaPolicy.php
│ ├── BoMonPolicy.php
│ ├── HeDaoTaoPolicy.php
│ └── NganhPolicy.php
│
├── Providers/
│ └── AuthServiceProvider.php
│
resources/views/
├── layouts/
│ ├── app.blade.php (Bootstrap 5 layout)
│ ├── \_sidebar.blade.php
│ ├── \_topbar.blade.php
│ └── \_footer.blade.php
├── dashboard/
│ └── index.blade.php
├── ctdt/
│ ├── index.blade.php
│ ├── create.blade.php
│ ├── edit.blade.php
│ └── show.blade.php
├── hoc-phan/
│ ├── index.blade.php
│ ├── create.blade.php
│ ├── edit.blade.php
│ └── show.blade.php
├── khoa/
│ └── ...
└── ctdt-approval/
└── pending.blade.php

database/
├── migrations/
│ ├── 2024_01_01_000001_create_khoa_table.php
│ ├── 2024_01_01_000002_create_bo_mon_table.php
│ ├── 2024_01_01_000010_create_chuong_trinh_dao_tao_table.php
│ └── ...
└── seeders/
└── DatabaseSeeder.php

routes/
└── web.php
\`\`\`

## Use Cases Hỗ Trợ

### UC14: Xây dựng CTĐT

-   **UC14.1**: Tạo mới CTĐT (trạng thái draft)
-   **UC14.2**: Sao chép CTĐT từ niên khóa trước
-   **UC14.3**: Sửa CTĐT
-   **UC14.4**: Xoá CTĐT (khi draft)
-   **UC14.5**: Xem chi tiết CTĐT (Khối → Học phần)
-   **UC14.6**: Thêm học phần vào CTĐT (multi-select, gán khối, học kỳ, loại, thứ tự)
-   **UC14.7**: Loại bỏ học phần
-   **UC14.8**: Quản lý ràng buộc (tiên quyết/song hành)
-   **UC14.9**: Quản lý tương đương học phần
-   **UC14.10**: Gửi phê duyệt CTĐT

### UC15: Phê duyệt CTĐT (Admin)

-   Trạng thái: `draft → pending → approved → published → archived`
-   Phê duyệt / Từ chối (với lý do)
-   Công bố CTĐT

## Phân Quyền

| Vai trò        | Quyền hạn                                            |
| -------------- | ---------------------------------------------------- |
| **Admin**      | Toàn quyền, quản lý danh mục, phê duyệt/publish CTĐT |
| **Khoa**       | CRUD CTĐT của khoa mình, quản lý học phần khoa       |
| **Giảng viên** | Xem CTĐT/học phần (read-only)                        |
| **Sinh viên**  | Xem CTĐT published (read-only)                       |

**Row-level security**: Khoa chỉ thấy dữ liệu của khoa mình, trừ Admin.

## Trạng Thái CTĐT

-   `draft`: Nháp, chỉnh sửa được
-   `pending`: Chờ duyệt từ Admin
-   `approved`: Đã phê duyệt, chờ công bố
-   `published`: Đã công bố, công khai
-   `archived`: Lưu trữ

## Routes API

### Dashboard

-   `GET /dashboard` - Xem dashboard

### CTDT

-   `GET /ctdt` - Danh sách CTĐT
-   `GET /ctdt/create` - Tạo CTĐT
-   `POST /ctdt` - Lưu CTĐT mới
-   `GET /ctdt/{id}` - Xem chi tiết CTĐT
-   `GET /ctdt/{id}/edit` - Sửa CTĐT
-   `PUT /ctdt/{id}` - Cập nhật CTĐT
-   `DELETE /ctdt/{id}` - Xoá CTĐT
-   `POST /ctdt/{id}/clone` - Sao chép CTĐT
-   `POST /ctdt/{id}/send-for-approval` - Gửi phê duyệt

### CTDT Approval (Admin)

-   `GET /ctdt-approval/pending` - Xem CTĐT chờ duyệt
-   `POST /ctdt-approval/{id}/approve` - Phê duyệt
-   `POST /ctdt-approval/{id}/publish` - Công bố
-   `POST /ctdt-approval/{id}/reject` - Từ chối

### CTDT Items

-   `POST /ctdt/{id}/add-hoc-phan` - Thêm học phần
-   `DELETE /ctdt/{id}/hoc-phan/{hocPhanId}` - Xoá học phần
-   `POST /ctdt/{id}/update-order` - Cập nhật thứ tự

### Học phần

-   `GET /hoc-phan` - Danh sách
-   `GET /hoc-phan/create` - Tạo
-   `POST /hoc-phan` - Lưu
-   `GET /hoc-phan/{id}` - Chi tiết
-   `GET /hoc-phan/{id}/edit` - Sửa
-   `PUT /hoc-phan/{id}` - Cập nhật
-   `DELETE /hoc-phan/{id}` - Xoá

### Admin Only

-   `GET /khoa` - Quản lý khoa
-   `GET /bo-mon` - Quản lý bộ môn
-   `GET /he-dao-tao` - Quản lý hệ đào tạo
-   `GET /nganh` - Quản lý ngành
-   `GET /nien-khoa` - Quản lý niên khóa
-   `GET /user` - Quản lý người dùng

## Database Schema

### Master Tables

-   `khoa`: Danh sách khoa
-   `bo_mon`: Danh sách bộ môn (thuộc khoa)
-   `he_dao_tao`: Danh sách hệ đào tạo
-   `nganh`: Danh sách ngành
-   `chuyen_nganh`: Danh sách chuyên ngành
-   `nien_khoa`: Danh sách niên khóa
-   `khoa_hoc`: Danh sách khóa học
-   `hoc_phan`: Danh sách học phần
-   `khoi_kien_thuc`: Danh sách khối kiến thức

### CTDT Tables

-   `chuong_trinh_dao_tao`: Chương trình đào tạo
-   `ctdt_khoi`: Khối trong CTĐT
-   `ctdt_hoc_phan`: Học phần trong CTĐT (có `thu_tu` để sắp xếp)
-   `ctdt_rang_buoc`: Ràng buộc học phần (tiên quyết/song hành)
-   `ctdt_tuong_duong`: Học phần tương đương

## Testing

Chạy tests:
\`\`\`bash
php artisan test
\`\`\`

## Ghi Chú

-   **Breeze đã cài sẵn**: Không sửa phần Auth (Tailwind CSS mặc định)
-   **Bootstrap 5 CDN**: Dashboard dùng Bootstrap 5 qua CDN, không npm
-   **Chart.js CDN**: Biểu đồ dùng Chart.js từ CDN
-   **Activity Log**: Tự động ghi lại mọi hành động

## Support

Liên hệ: support@example.com
