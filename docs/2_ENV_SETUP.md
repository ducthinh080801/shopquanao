Tạo file .env ở root dự án với nội dung mẫu sau:

# CodeIgniter Environment
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

# Database Configuration
database.default.hostname = localhost
database.default.database = ecommerce_db
database.default.username = root
database.default.password = your_password_here
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

# Stripe Keys
stripe.publishable_key = pk_test_yourkey
stripe.secret_key = sk_test_yourkey

# Email Configuration (nếu cần)
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = your_email@gmail.com
email.SMTPPass = your_email_password
email.SMTPPort = 587
email.mailType = html