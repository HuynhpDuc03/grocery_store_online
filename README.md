# Tạp Hóa Online

Đồ án môn Thiết Kế Web sử dụng PHP thuần, MySQL, HTML, CSS và JavaScript.

## Cài đặt

1. Đặt thư mục vào `htdocs` (XAMPP) hoặc thư mục máy chủ của Laragon.
2. Import `database/schema.sql` vào MySQL/phpMyAdmin.
3. Kiểm tra thông tin kết nối trong `config/database.php` (mặc định MySQL port `3307`).
4. Truy cập `http://localhost/<ten-thu-muc>/`.

## Các trang chính

- `pages/products.php`: danh sách sản phẩm và thêm vào giỏ hàng.
- `pages/cart.php`: cập nhật/xóa sản phẩm và đặt hàng.
- `admin/products.php`: thêm, sửa, xóa sản phẩm.

Để tạo tài khoản quản trị, đăng ký tài khoản tại `pages/register.php`, sau đó chạy lệnh sau trong phpMyAdmin:

```sql
UPDATE users SET role = 'admin' WHERE email = 'email-cua-ban@example.com';
```

Các file `.html` trong `pages/` được giữ lại làm bản giao diện cũ; các tính năng động sử dụng phiên bản `.php` tương ứng.
