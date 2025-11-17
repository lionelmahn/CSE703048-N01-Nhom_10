# CSE703048-1-1-25-N01-Nhom_10
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
## Cài Đặt

### 1. Clone hoặc tải mã nguồn

```bash
git clone https://github.com/lionelmahn/CSE703048-N01-Nhom_10.git
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
DB_DATABASE=<name_db_local>
DB_USERNAME=root
DB_PASSWORD= <pass_db_local>
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

## 🔐 Tài khoản DEMO

Dùng để đăng nhập và trải nghiệm hệ thống.

> **Mật khẩu mặc định tất cả tài khoản:** `password`

| Vai trò | Email |
|--------|--------------------------------|
| Admin | admin@example.com |
| Khoa CNTT | khoa.cntt@example.com |
| Khoa KT | khoa.kt@example.com |
| Giảng viên 1 | gv1@example.com |
| Giảng viên 2 | gv2@example.com |
| Giảng viên 3 | gv3@example.com |
| Sinh viên 1 | sv1@example.com |
| Sinh viên 2 | sv2@example.com |
| Sinh viên 3 | sv3@example.com |

---

