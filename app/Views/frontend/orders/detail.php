<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold">Chi tiết đơn hàng</h1>
                    <p class="text-gray-600">Mã đơn: <span class="font-bold text-indigo-600">#<?= esc($order['order_number']) ?></span></p>
                </div>
                <?php
                    $rawStatus = mb_strtolower(trim($order['status'] ?? ''), 'UTF-8');
                    $statusMap = [
                        'pending' => ['pending', 'chờ xử lý', 'cho xu ly'],
                        'processing' => ['processing', 'đang xử lý', 'dang xu ly'],
                        'shipped' => ['shipped', 'shipped', 'delivering', 'out for delivery', 'đang giao', 'dang giao'],
                        'delivered' => ['delivered', 'delivered', 'done', 'finished', 'thành công', 'hoàn thành', 'thanh cong', 'hoan thanh'],
                        'cancelled' => ['cancelled', 'canceled', 'đã hủy', 'da huy'],
                    ];

                    $statusKey = null;
                    foreach ($statusMap as $key => $aliases) {
                        if (in_array($rawStatus, array_map(fn($a) => mb_strtolower($a, 'UTF-8'), $aliases), true)) {
                            $statusKey = $key;
                            break;
                        }
                    }

                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $statusLabels = [
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'shipped' => 'Đang giao',
                        'delivered' => 'Thành công',
                        'cancelled' => 'Đã hủy',
                    ];

                    $statusClass = $statusKey ? $statusClasses[$statusKey] : 'bg-gray-100 text-gray-800';
                    $statusLabel = $statusKey ? $statusLabels[$statusKey] : esc($order['status']);
                ?>
                <span class="px-6 py-3 rounded-lg text-lg font-bold <?= $statusClass ?>">
                    <?= $statusLabel ?>
                </span>
            </div>
            <p class="text-gray-600">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
        </div>

        <!-- Shipping Info -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4"><i class="fas fa-shipping-fast mr-2 text-indigo-600"></i>Thông tin giao hàng</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Người nhận</p>
                    <p class="font-bold"><?= esc($order['shipping_name']) ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Số điện thoại</p>
                    <p class="font-bold"><?= esc($order['shipping_phone']) ?></p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-600 text-sm">Địa chỉ</p>
                    <p class="font-bold"><?= esc($order['shipping_address']) ?></p>
                </div>
                <?php if (!empty($order['notes'])): ?>
                    <div class="md:col-span-2">
                        <p class="text-gray-600 text-sm">Ghi chú</p>
                        <p class="font-bold"><?= esc($order['notes']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4"><i class="fas fa-box mr-2 text-indigo-600"></i>Sản phẩm đã đặt</h2>
            <div class="space-y-4">
                <?php foreach ($order_items as $item): ?>
                    <div class="flex gap-4 pb-4 border-b last:border-b-0">
                        <img src="<?= esc($item['product_image'] ?? '/assets/placeholder.png') ?>" 
                             alt="<?= esc($item['product_name']) ?>" 
                             class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-bold text-lg"><?= esc($item['product_name']) ?></h3>
                            <p class="text-gray-600 text-sm">
                                <?php if (!empty($item['size'])): ?>Kích thước: <?= esc($item['size']) ?><?php endif; ?>
                                <?php if (!empty($item['color'])): ?> | Màu: <?= esc($item['color']) ?><?php endif; ?>
                            </p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-gray-600">Số lượng: <span class="font-bold"><?= $item['quantity'] ?></span></p>
                                <p class="text-indigo-600 font-bold text-lg"><?= number_format($item['subtotal']) ?>đ</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4"><i class="fas fa-credit-card mr-2 text-indigo-600"></i>Thông tin thanh toán</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-lg">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span class="font-bold"><?= number_format($order['total_amount']) ?>đ</span>
                </div>
                <div class="flex justify-between text-lg">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span class="font-bold text-green-600">Miễn phí</span>
                </div>
                <div class="border-t pt-3 flex justify-between text-2xl">
                    <span class="font-bold">Tổng cộng:</span>
                    <span class="font-bold text-indigo-600"><?= number_format($order['total_amount']) ?>đ</span>
                </div>
                <div class="pt-3 border-t">
                    <p class="text-gray-600">Phương thức: <span class="font-bold">Stripe Card</span></p>
                    <?php if (!empty($payment)): ?>
                        <p class="text-gray-600">Mã giao dịch: <span class="font-bold"><?= esc($payment['transaction_id']) ?></span></p>
                        <p class="text-gray-600">Trạng thái: 
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg font-bold">
                                <?= ucfirst($payment['status']) ?>
                            </span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="/orders" class="flex-1 border border-indigo-600 text-indigo-600 py-3 rounded-lg font-bold text-center hover:bg-indigo-50">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
            <?php if ($order['status'] === 'completed'): ?>
                <a href="/orders/invoice/<?= esc($order['order_number']) ?>" 
                   class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold text-center hover:bg-indigo-700">
                    <i class="fas fa-file-invoice mr-2"></i>Xem hóa đơn
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
