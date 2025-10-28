<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #<?= esc($invoice['invoice_number']) ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 28px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h3 {
            background-color: #F3F4F6;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 30%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #F3F4F6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-table {
            width: 300px;
            margin-left: auto;
        }
        .total-table td {
            padding: 8px;
        }
        .total-row {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>SHOP QUẦN ÁO</h1>
        <p>Địa chỉ: TP.Hà Nội</p>
        <p>Điện thoại: 0336666666 | Email: shop@email.com</p>
    </div>

    <!-- Invoice Info -->
    <div class="info-section">
        <h3>THÔNG TIN HÓA ĐƠN</h3>
        <table class="info-table">
            <tr>
                <td>Số hóa đơn:</td>
                <td><?= esc($invoice['invoice_number']) ?></td>
            </tr>
            <tr>
                <td>Ngày phát hành:</td>
                <td><?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?></td>
            </tr>
            <tr>
                <td>Mã đơn hàng:</td>
                <td>#<?= esc($invoice['order_number']) ?></td>
            </tr>
        </table>
    </div>

    <!-- Customer Info -->
    <div class="info-section">
        <h3>THÔNG TIN KHÁCH HÀNG</h3>
        <table class="info-table">
            <tr>
                <td>Họ và tên:</td>
                <td><?= esc($invoice['customer_name']) ?></td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td><?= esc($invoice['customer_phone']) ?></td>
            </tr>
            <tr>
                <td>Địa chỉ:</td>
                <td><?= esc($invoice['customer_address']) ?></td>
            </tr>
        </table>
    </div>

    <!-- Products -->
    <h3>CHI TIẾT SẢN PHẨM</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">STT</th>
                <th style="width: 45%;">Sản phẩm</th>
                <th class="text-center" style="width: 10%;">SL</th>
                <th class="text-right" style="width: 20%;">Đơn giá</th>
                <th class="text-right" style="width: 20%;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1; ?>
            <?php foreach ($invoice['items'] as $item): ?>
                <tr>
                    <td class="text-center"><?= $index++ ?></td>
                    <td>
                        <?= esc($item['product_name']) ?>
                        <?php if (!empty($item['size']) || !empty($item['color'])): ?>
                            <br><small style="color: #666;">
                                <?php if (!empty($item['size'])): ?>Size: <?= esc($item['size']) ?><?php endif; ?>
                                <?php if (!empty($item['color'])): ?> | Màu: <?= esc($item['color']) ?><?php endif; ?>
                            </small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-right"><?= number_format($item['price']) ?>đ</td>
                    <td class="text-right"><?= number_format($item['subtotal']) ?>đ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-section">
        <table class="total-table">
            <tr>
                <td>Tạm tính:</td>
                <td class="text-right"><?= number_format($invoice['total_amount']) ?>đ</td>
            </tr>
            <tr>
                <td>Phí vận chuyển:</td>
                <td class="text-right" style="color: green;">Miễn phí</td>
            </tr>
            <tr class="total-row">
                <td>TỔNG CỘNG:</td>
                <td class="text-right" style="color: #4F46E5;"><?= number_format($invoice['total_amount']) ?>đ</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Cảm ơn quý khách đã mua hàng!</strong></p>
        <p>Hotline: 0123 456 789 | Email: shop@email.com</p>
        <p>Website: shop-quan-ao.com</p>
    </div>
</body>
</html>
