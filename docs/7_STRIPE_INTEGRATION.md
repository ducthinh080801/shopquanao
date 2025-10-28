Tạo tài khoản test trên stripe.com

Lấy Publishable key và Secret key.

Cấu hình trong .env như trên.

Sử dụng test card:

4242 4242 4242 4242
MM/YY
CVC

Sau thanh toán thành công:

Ghi vào bảng payments.

Sinh PDF hóa đơn và lưu vào bảng invoices.

Gửi email xác nhận đơn hàng cho người dùng.

Sau khi thanh toán, tạo hóa đơn và lưu vào lịch sử thanh toán.