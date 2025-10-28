<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <a href="/admin/orders" class="text-indigo-600 hover:text-indigo-700">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold">Đơn hàng #<?= esc($order['order_number']) ?></h2>
                    <p class="text-gray-600">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                </div>
                <select onchange="updateStatus(this.value)" 
                        class="px-4 py-2 rounded-lg font-bold border <?php
                    echo match($order['status']) {
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'shipping' => 'bg-purple-100 text-purple-800 border-purple-300',
                        'completed' => 'bg-green-100 text-green-800 border-green-300',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                        default => 'bg-gray-100 text-gray-800 border-gray-300'
                    };
                ?>">
                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                    <option value="shipping" <?= $order['status'] === 'shipping' ? 'selected' : '' ?>>Đang giao</option>
                    <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>

            <h3 class="font-bold text-lg mb-4">Sản phẩm</h3>
            <div class="space-y-4">
                <?php foreach ($order_items as $item): ?>
                    <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-bold"><?= esc($item['product_name']) ?></p>
                            <p class="text-sm text-gray-600">
                                <?php if (!empty($item['size'])): ?>Size: <?= esc($item['size']) ?><?php endif; ?>
                                <?php if (!empty($item['color'])): ?> | Màu: <?= esc($item['color']) ?><?php endif; ?>
                            </p>
                            <p class="text-sm text-gray-600">Số lượng: <?= $item['quantity'] ?> x <?= number_format($item['price']) ?>đ</p>
                        </div>
                        <p class="font-bold text-lg text-indigo-600"><?= number_format($item['subtotal']) ?>đ</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-lg mb-4">Thông tin thanh toán</h3>
            <?php if (!empty($payment)): ?>
                <div class="space-y-2">
                    <p><span class="text-gray-600">Phương thức:</span> <span class="font-bold">Stripe Card</span></p>
                    <p><span class="text-gray-600">Mã giao dịch:</span> <span class="font-bold"><?= esc($payment['transaction_id']) ?></span></p>
                    <p><span class="text-gray-600">Trạng thái:</span> 
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded font-bold">
                            <?= ucfirst($payment['status']) ?>
                        </span>
                    </p>
                    <p><span class="text-gray-600">Số tiền:</span> <span class="font-bold text-xl text-indigo-600"><?= number_format($payment['amount']) ?>đ</span></p>
                </div>
            <?php else: ?>
                <p class="text-gray-600">Chưa có thông tin thanh toán</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-lg mb-4">Thông tin khách hàng</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Họ tên</p>
                    <p class="font-bold"><?= esc($order['shipping_name']) ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Số điện thoại</p>
                    <p class="font-bold"><?= esc($order['shipping_phone']) ?></p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Địa chỉ</p>
                    <p class="font-bold"><?= esc($order['shipping_address']) ?></p>
                </div>
                <?php if (!empty($order['notes'])): ?>
                    <div>
                        <p class="text-gray-600 text-sm">Ghi chú</p>
                        <p class="font-bold"><?= esc($order['notes']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-lg mb-4">Tổng kết</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span class="font-bold"><?= number_format($order['total_amount']) ?>đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span class="font-bold text-green-600">Miễn phí</span>
                </div>
                <div class="border-t pt-2 flex justify-between text-xl">
                    <span class="font-bold">Tổng:</span>
                    <span class="font-bold text-indigo-600"><?= number_format($order['total_amount']) ?>đ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (!confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng?')) {
        location.reload();
        return;
    }
    
    $.ajax({
        url: '/admin/orders/update-status',
        method: 'POST',
        data: {
            order_id: <?= $order['id'] ?>,
            status: status
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message);
            } else {
                showToast(response.message, 'error');
                location.reload();
            }
        }
    });
}
</script>

<?= $this->endSection() ?>
