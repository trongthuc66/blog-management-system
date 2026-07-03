# 📝 Blog Management System

Hệ thống quản lý Blog được phát triển bằng **PHP** và **MySQL**, cho phép quản trị viên quản lý bài viết, chủ đề, người dùng và bình luận thông qua trang quản trị.

---

## 📖 Giới thiệu

Đây là dự án website Blog Management được xây dựng nhằm mô phỏng một hệ thống quản lý blog cơ bản. Dự án áp dụng mô hình quản lý dữ liệu với PHP và MySQL, đồng thời xây dựng giao diện trực quan giúp người dùng và quản trị viên dễ dàng thao tác.

---

## ✨ Chức năng

### 👤 Người dùng

- Đăng ký tài khoản
- Đăng nhập / Đăng xuất
- Xem danh sách bài viết
- Xem chi tiết bài viết

### 👨‍💼 Quản trị viên

- Quản lý bài viết (Thêm / Sửa / Xóa)
- Quản lý chủ đề
- Quản lý người dùng
- Quản lý bình luận
- Dashboard quản trị

---

## 🛠️ Công nghệ sử dụng

| Công nghệ | Mô tả |
|-----------|-------|
| PHP | Xử lý phía Server |
| MySQL | Quản lý cơ sở dữ liệu |
| HTML5 | Xây dựng giao diện |
| CSS3 | Thiết kế giao diện |
| JavaScript | Xử lý tương tác |
| Git | Quản lý mã nguồn |
| GitHub | Lưu trữ dự án |

---

## 📂 Cấu trúc dự án

```
blog-management-system
│
├── admin/
├── app/
├── assets/
├── css/
├── images/
├── js/
├── index.php
├── login.php
├── register.php
└── blog.sql
```

---

## ⚙️ Hướng dẫn cài đặt

### 1. Clone repository

```bash
git clone https://github.com/trongthuc66/blog-management-system.git
```

### 2. Đưa dự án vào thư mục `htdocs` của XAMPP

### 3. Khởi động Apache và MySQL

### 4. Tạo Database trong phpMyAdmin

Tên Database:

```
blog
```

### 5. Import file

```
blog.sql
```

### 6. Cấu hình kết nối Database

Mở file:

```
app/database/connect.php
```

Điền thông tin:

```php
$host = "localhost";
$user = "root";
$password = "";
$db_name = "blog";
```

### 7. Chạy dự án

```
http://localhost/blog
```

---

# 📸 Hình ảnh dự án

## 🏠 Trang chủ

> <img width="877" height="1328" alt="image" src="https://github.com/user-attachments/assets/ca1999f7-f87c-4b94-bac9-586286d2bb85" />


---

## 🔐 Trang đăng nhập

> <img width="940" height="289" alt="image" src="https://github.com/user-attachments/assets/285e34a8-038e-4fc4-814f-bda3b19dfe9c" />


---

## 📊 Dashboard quản trị

> <img width="940" height="492" alt="image" src="https://github.com/user-attachments/assets/3c7e2efb-d45b-418a-9104-ac4cfff63f3a" />


---

## 📝 Quản lý bài viết

> <img width="940" height="492" alt="image" src="https://github.com/user-attachments/assets/9c77d885-2f33-4e17-80cb-d5918b8cc756" />


---

## 👥 Quản lý người dùng

> <img width="940" height="364" alt="image" src="https://github.com/user-attachments/assets/e81a8ad3-e5c1-4b08-b6f5-d3e394b8ad1c" />


---

## 👨‍💻 Vai trò

- Phát triển và mở rộng hệ thống Blog Management.
- Tùy chỉnh giao diện người dùng.
- Xây dựng các chức năng quản lý bài viết, chủ đề và người dùng.
- Kết nối cơ sở dữ liệu MySQL.
- Quản lý mã nguồn bằng Git và GitHub.

---

## 🚀 Điểm nổi bật

- Giao diện quản trị trực quan.
- Thực hiện đầy đủ các thao tác CRUD.
- Phân quyền người dùng.
- Kết nối và thao tác dữ liệu bằng MySQL.
- Áp dụng Git để quản lý phiên bản.

---

## 📌 Repository

👉 **GitHub Repository**

https://github.com/trongthuc66/blog-management-system

---

## 👤 Tác giả

**Nguyễn Trọng Thức**

GitHub: https://github.com/trongthuc66
