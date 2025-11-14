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
## 🧩 Mô tả dự án

Hệ thống được xây dựng nhằm số hóa toàn bộ quy trình **thiết kế – phê duyệt – công bố** Chương trình Đào tạo (CTĐT) trong trường đại học.

Dự án tập trung vào các nghiệp vụ chính:

- Quản lý danh mục học thuật:
  - Hệ đào tạo, Bậc học, Loại hình đào tạo  
  - Khoa, Bộ môn  
  - Ngành, Chuyên ngành  
  - Học phần, Khối kiến thức  
  - Niên khóa, Khóa học  
- Xây dựng cấu trúc CTĐT theo chuẩn đầu ra  
- Tạo và quản lý ràng buộc học phần (tiên quyết, song hành)  
- Luồng phê duyệt CTĐT điện tử  
- Quản lý phiên bản CTĐT theo từng khóa sinh viên  

---

## 🎯 Bối cảnh & Vấn đề

Việc quản lý CTĐT hiện nay chủ yếu được thực hiện thủ công qua:

- Bảng tính (Excel)  
- Văn bản giấy  
- Trao đổi qua email  

Điều này dẫn đến:

- Dữ liệu phân tán, khó đồng bộ  
- Khó kiểm soát ràng buộc giữa các học phần  
- Quy trình phê duyệt tốn thời gian, dễ sai sót  
- Không có một nguồn dữ liệu CTĐT “duy nhất và đáng tin cậy” cho toàn trường  

Hệ thống được thiết kế để chuyển đổi quy trình trên sang mô hình số hóa, tập trung, có kiểm soát.

---

## 🎯 Mục tiêu hệ thống

- **Tập trung hóa dữ liệu CTĐT**  
  Xây dựng một cơ sở dữ liệu duy nhất cho toàn bộ thông tin chương trình đào tạo.

- **Tự động hóa kiểm tra logic chương trình**  
  Hỗ trợ kiểm tra tín chỉ, các ràng buộc tiên quyết – song hành, cấu trúc khối kiến thức.

- **Chuẩn hóa & minh bạch quy trình phê duyệt**  
  Hỗ trợ luồng phê duyệt điện tử từ Khoa đến Phòng Đào tạo.

- **Giảm tải công việc hành chính**  
  Hạn chế thao tác thủ công lặp lại, giảm lỗi nhập liệu.

- **Giao diện trực quan, dễ sử dụng**  
  Hỗ trợ người dùng dễ dàng thiết kế và điều chỉnh CTĐT.

---

## 👨‍🏫 Người dùng mục tiêu

Hệ thống phục vụ 4 nhóm người dùng chính:

- **Phòng Đào tạo (Admin)**  
  Quản trị toàn hệ thống, phê duyệt CTĐT, quản lý danh mục dùng chung.

- **Khoa / Bộ môn**  
  Xây dựng và đề xuất CTĐT, cập nhật học phần, khối kiến thức.

- **Giảng viên**  
  Tra cứu CTĐT, học phần, cấu trúc chương trình phục vụ giảng dạy và cố vấn học tập.

- **Sinh viên**  
  Xem lộ trình học tập, cấu trúc chương trình đào tạo áp dụng cho khóa của mình.

---

## 📦 Phạm vi (Scope)

### ✅ Phạm vi MVP (hiện tại)

- Quản lý cấu trúc CTĐT:
  - Tạo mới, chỉnh sửa, sao chép, lưu trữ phiên bản CTĐT  
- Quản lý học phần & khối kiến thức:
  - Gán học phần vào khối kiến thức, đánh dấu bắt buộc/tự chọn  
- Quản lý ràng buộc học phần:
  - Thiết lập tiên quyết, song hành giữa các học phần  
- Quy trình phê duyệt chương trình:
  - Gửi yêu cầu phê duyệt CTĐT  
  - Phê duyệt / từ chối / yêu cầu chỉnh sửa CTĐT  

### ❌ Ngoài phạm vi (dự kiến phát triển sau)

- Theo dõi tiến độ học tập của sinh viên  
- Hỗ trợ đăng ký học phần & kiểm tra điều kiện trực tiếp theo hồ sơ sinh viên  
- Quản lý điểm, kết quả học tập  
- Quản lý tài chính, học phí, thư viện  
- Lập thời khóa biểu chi tiết  

---

## 🧠 Thuật ngữ chính

| Thuật ngữ                | Tiếng Anh              | Giải thích ngắn gọn                                      |
|--------------------------|------------------------|----------------------------------------------------------|
| Chương trình đào tạo     | Curriculum             | Tập hợp có cấu trúc các học phần cho một ngành cụ thể   |
| Học phần                 | Course / Module        | Đơn vị kiến thức có số tín chỉ xác định                 |
| Tín chỉ                  | Credit                 | Đơn vị đo khối lượng học tập                            |
| Học phần tiên quyết      | Prerequisite Course    | Học phần phải hoàn thành trước khi học học phần khác    |
| Học phần song hành       | Corequisite Course     | Học phần cần học đồng thời với học phần khác            |
| Khối kiến thức           | Knowledge Block        | Nhóm các học phần cùng mục tiêu đào tạo                 |
| Phiên bản CTĐT           | Curriculum Version     | CTĐT gắn với một khóa tuyển sinh cụ thể                 |
| Khóa học                 | Cohort                 | Nhóm sinh viên nhập học cùng năm                        |

---

## ⚙️ Yêu cầu kỹ thuật bổ sung

### Hiệu năng

- 90% truy vấn cơ bản trả về kết quả trong **< 1.5 giây**  
- Báo cáo phức tạp được tạo trong **< 5 giây**  
- Hỗ trợ tối thiểu **500 người dùng đồng thời** ở thời điểm cao điểm  

### Bảo mật

- Áp dụng **RBAC (Role-Based Access Control)**  
- Mã hóa dữ liệu nhạy cảm (mật khẩu, thông tin cá nhân)  
- Mật khẩu tuân thủ chính sách mạnh (≥ 8 ký tự, có chữ hoa, thường, số, ký tự đặc biệt)  

### Khả dụng & Trải nghiệm người dùng

- Giao diện trực quan, dễ sử dụng  
- Thiết kế responsive, hỗ trợ máy tính, tablet, điện thoại  
- Thông báo lỗi rõ ràng, hướng dẫn được cách khắc phục  

### Khả năng mở rộng

- Kiến trúc module, dễ bổ sung chức năng mới  
- Khả năng mở rộng để phục vụ đến **30.000 người dùng** và hàng triệu bản ghi trong tương lai  

---

## 🏗️ Các nhóm chức năng chính

### R1. Quản lý truy cập hệ thống

- Đăng nhập (UC1)  
- Quên mật khẩu (UC2)  
- Đổi mật khẩu (UC3)  

### R2. Quản lý tổ chức

- Quản lý Khoa (UC4)  
- Quản lý Bộ môn (UC5)  
- Quản lý Người dùng & phân quyền (UC6)  

### R3. Quản lý danh mục học thuật

- Quản lý Hệ đào tạo (UC7)  
- Quản lý Khối kiến thức (UC8)  
- Quản lý Ngành đào tạo (UC9)  
- Quản lý Chuyên ngành (UC10)  
- Quản lý Học phần (UC11)  
- Quản lý Niên khóa (UC12)  
- Quản lý Khóa học (UC13)  
- Quản lý Bậc học (UC14)  
- Quản lý Loại hình đào tạo (UC15)  

### R4. Xây dựng & quản lý CTĐT

- Quản lý thông tin CTĐT (UC16)  
- Quản lý học phần CTĐT (UC17)  
- Quản lý ràng buộc học phần (UC18)  
- Gửi yêu cầu phê duyệt CTĐT (UC19)  
- Phê duyệt CTĐT (UC20)  

---

## 🛠️ Công nghệ sử dụng

- **Back-end:** PHP & Laravel Framework  
- **Front-end:** Blade template, Bootstrap / Tailwind CSS, JavaScript  
- **Database:** MySQL  
- **Quản lý mã nguồn:** Git / GitHub  

---

## 📂 Gợi ý cấu trúc thư mục (Laravel)

> Lưu ý: phần này có thể điều chỉnh lại cho đúng với repo thực tế của bạn.

- `app/Models` – Các lớp mô hình (Khoa, Bộ môn, Ngành, Chuyên ngành, Học phần, CTĐT, …)  
- `app/Http/Controllers` – Các controller xử lý nghiệp vụ từng module  
- `database/migrations` – Các file migration tạo bảng CSDL  
- `resources/views` – Giao diện Blade (quản lý danh mục, CTĐT, phê duyệt, …)  
- `routes/web.php` – Định tuyến cho các chức năng web  
- `public/` – Tài nguyên tĩnh (CSS, JS, images)  

---

## ✅ Kết quả đạt được

- Hoàn thành phân tích yêu cầu, mô hình hóa use-case, sequence, class, database  
- Xây dựng & triển khai hệ thống theo kiến trúc 3 lớp trên nền tảng Laravel & MySQL  
- Hỗ trợ đầy đủ chức năng quản lý cấu trúc CTĐT trong phạm vi đã đặt ra  
- Giao diện thân thiện, dễ thao tác cho Phòng Đào tạo và Khoa  

---

## 🚀 Hướng phát triển

- Tích hợp với hệ thống đăng ký học phần của sinh viên  
- Kiểm tra điều kiện tiên quyết dựa trên hồ sơ thực tế của từng sinh viên  
- Tự động sinh lộ trình học tập cá nhân hóa  
- Tích hợp API với hệ thống ERP/học vụ hiện có  
- Mở rộng sang quản lý tiến độ và đánh giá chuẩn đầu ra chương trình đào tạo  

---