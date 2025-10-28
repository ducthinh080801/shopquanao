<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Invoice Actions -->
        <div class="flex justify-between items-center mb-6">
            <a href="/orders" class="text-indigo-600 hover:text-indigo-700">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
            <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-print mr-2"></i>In hóa đơn
            </button>
        </div>

        <!-- Invoice -->
        <div class="bg-white rounded-xl shadow-lg p-8 print:shadow-none" id="invoice">
            <!-- Header -->
            <div class="border-b pb-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-indigo-600 mb-2">
                            <i class="fas fa-store mr-2"></i>Shop Quần Áo
                        </h1>
                        <p class="text-gray-600">Địa chỉ: TP.Hà Nội</p>
                        <p class="text-gray-600">Điện thoại: 0336666666</p>
                        <p class="text-gray-600">Email: shop@email.com</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-2xl font-bold mb-2">HÓA ĐƠN</h2>
                        <p class="text-gray-600">Số: <span class="font-bold"><?= esc($invoice['invoice_number']) ?></span></p>
                        <p class="text-gray-600">Ngày: <?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="mb-6">
                <h3 class="font-bold text-lg mb-3">Thông tin khách hàng</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="mb-2"><span class="font-bold">Họ tên:</span> <?= esc($order['shipping_name']) ?></p>
                    <p class="mb-2"><span class="font-bold">Số điện thoại:</span> <?= esc($order['shipping_phone']) ?></p>
                    <p class="mb-2"><span class="font-bold">Địa chỉ:</span> <?= esc($order['shipping_address']) ?></p>
                    <p class="mb-2"><span class="font-bold">Đơn hàng:</span> #<?= esc($order['order_number']) ?></p>
                </div>
            </div>

            <!-- Products Table -->
            <div class="mb-6">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold">STT</th>
                            <th class="px-4 py-3 text-left font-bold">Sản phẩm</th>
                            <th class="px-4 py-3 text-center font-bold">Số lượng</th>
                            <th class="px-4 py-3 text-right font-bold">Đơn giá</th>
                            <th class="px-4 py-3 text-right font-bold">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1; ?>
                        <?php foreach ($order_items as $item): ?>
                            <tr class="border-b">
                                <td class="px-4 py-3"><?= $index++ ?></td>
                                <td class="px-4 py-3">
                                    <p class="font-bold"><?= esc($item['product_name']) ?></p>
                                    <?php if (!empty($item['size']) || !empty($item['color'])): ?>
                                        <p class="text-sm text-gray-600">
                                            <?php if (!empty($item['size'])): ?>Size: <?= esc($item['size']) ?><?php endif; ?>
                                            <?php if (!empty($item['color'])): ?> | Màu: <?= esc($item['color']) ?><?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-center"><?= $item['quantity'] ?></td>
                                <td class="px-4 py-3 text-right"><?= number_format($item['price']) ?>đ</td>
                                <td class="px-4 py-3 text-right font-bold"><?= number_format($item['subtotal']) ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="border-t pt-6">
                <div class="flex justify-end">
                    <div class="w-64">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-bold"><?= number_format($order['total_amount']) ?>đ</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-bold text-green-600">Miễn phí</span>
                        </div>
                        <div class="flex justify-between border-t pt-2 text-xl">
                            <span class="font-bold">Tổng cộng:</span>
                            <span class="font-bold text-indigo-600"><?= number_format($order['total_amount']) ?>đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t text-center text-gray-600">
                <p class="mb-2">Cảm ơn quý khách đã mua hàng!</p>
                <p class="text-sm">Hotline: 0123 456 789 | Email: shop@email.com</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice, #invoice * {
        visibility: visible;
    }
    #invoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
}
</style>

<?= $this->endSection() ?>
