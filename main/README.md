# 🎓 BÁO CÁO BÀI TẬP LỚN  
## MÔN HỌC: PHÂN TÍCH VÀ THIẾT KẾ PHẦN MỀM  
### **ĐỀ TÀI: PHÁT TRIỂN PHẦN MỀM QUẢN LÝ CHƯƠNG TRÌNH ĐÀO TẠO**

---

## 🏫 Thông tin học phần

- **Môn học:** Phân tích và Thiết kế Phần mềm (N01)  
- **Tên đề tài:** Phát triển phần mềm Quản lý Chương trình Đào tạo  
- **Nhóm:** 10  
- **Giảng viên hướng dẫn:** **TS. Mai Thúy Nga**

---

## 👥 Thành viên thực hiện

| Họ và tên          | Mã sinh viên | Gmail |
|--------------------|--------------|---------------------------------------------|
| Nguyễn Kiêm Mạnh   | 23010909     | 23010909@st.phenikaa-uni.edu.vn             |
| Lê Đức Duy         | 23010772     | 23010772@st.phenikaa-uni.edu.vn             |
| Nguyễn Văn Mạnh   | 23010559     | 23010559@st.phenikaa-uni.edu.vn             |

---

# Hệ Thống Quản Lý Chương Trình Đào Tạo (CTĐT)

Hệ thống web quản lý chương trình đào tạo dành cho các trường đại học, viện đào tạo. Xây dựng bằng Laravel MVC thuần với Bootstrap 5 dashboard.

## Tính Năng Chính

-   **Quản lý tổ chức**: Khoa, Bộ môn, Hệ đào tạo, Ngành, Chuyên ngành
-   **Quản lý danh mục**: Học phần, Niên khóa, Khối kiến thức, Bậc học, Loại hình đào tạo, Khoá học
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

```bash
git clone <repository-url> ctdt-management
cd ctdt-management
```

### 2. Cài đặt Composer dependencies

```bash
composer install
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Cấu hình database trong `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ctdt_management
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Chạy migrations và seeders

```bash
php artisan migrate --seed
```

### 6. Cài đặt npm dependencies (cho Breeze auth UI)

```bash
npm install && npm run build
```

### 7. Khởi động server

```bash
php artisan serve
```

Truy cập: **http://localhost:8000**

## Tài Khoản Demo

| Email           | Role       | Password | Ghi chú            |
| --------------- | ---------- | -------- | ------------------ |
| admin@demo.test | admin      | password | Toàn quyền         |
| khoa@demo.test  | khoa       | password | Quản trị khoa CNTT |
| gv@demo.test    | giang_vien | password | Giảng viên         |
| sv@demo.test    | sinh_vien  | password | Sinh viên          |


## Phân Quyền

| Vai trò        | Quyền hạn                                            |
| -------------- | ---------------------------------------------------- |
| **Admin**      | Toàn quyền, quản lý danh mục, phê duyệt/publish CTĐT |
| **Khoa**       | CRUD CTĐT của khoa mình, quản lý học phần khoa       |
| **Giảng viên** | Xem CTĐT/học phần (read-only)                        |
| **Sinh viên**  | Xem CTĐT published (read-only)                       |

