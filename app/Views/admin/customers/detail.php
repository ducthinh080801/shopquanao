<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <a href="/admin/customers" class="text-indigo-600 hover:text-indigo-700">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Customer Info -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="text-center mb-6">
            <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user text-indigo-600 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold"><?= esc($customer['full_name']) ?></h2>
            <p class="text-gray-600"><?= esc($customer['email']) ?></p>
        </div>

        <div class="space-y-3 border-t pt-6">
            <div>
                <p class="text-gray-600 text-sm">Số điện thoại</p>
                <p class="font-bold"><?= esc($customer['phone'] ?? 'Chưa cập nhật') ?></p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Địa chỉ</p>
                <p class="font-bold"><?= esc($customer['address'] ?? 'Chưa cập nhật') ?></p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Ngày sinh</p>
                <p class="font-bold"><?= $customer['date_of_birth'] ? date('d/m/Y', strtotime($customer['date_of_birth'])) : 'Chưa cập nhật' ?></p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Ngày đăng ký</p>
                <p class="font-bold"><?= date('d/m/Y', strtotime($customer['created_at'])) ?></p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Trạng thái</p>
                <span class="px-3 py-1 rounded-lg font-bold <?= $customer['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                    <?= $customer['is_active'] ? 'Hoạt động' : 'Bị khóa' ?>
                </span>
            </div>
        </div>

        <button onclick="toggleStatus()" class="w-full mt-6 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
            <?= $customer['is_active'] ? 'Khóa tài khoản' : 'Mở khóa' ?>
        </button>
    </div>

    <!-- Statistics & Orders -->
    <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Tổng đơn hàng</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $stats['total_orders'] ?? 0 ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Tổng chi tiêu</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total_spent'] ?? 0) ?>đ</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Đánh giá</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $stats['total_reviews'] ?? 0 ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Lịch sử đơn hàng</h3>
            <?php if (!empty($orders)): ?>
                <div class="space-y-3">
                    <?php foreach ($orders as $order): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-bold text-indigo-600">#<?= esc($order['order_number']) ?></p>
                                <p class="text-sm text-gray-600"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg"><?= number_format($order['total_amount']) ?>đ</p>
                                <?php
                                    $rawStatus = strtolower(trim($order['status'] ?? ''));
                                    $statusMap = [
                                        'pending' => ['pending', 'chờ xử lý', 'cho xu ly'],
                                        'processing' => ['processing', 'đang xử lý', 'dang xu ly'],
                                        'shipping' => ['shipping', 'đang giao', 'dang giao'],
                                        'completed' => ['completed', 'thành công', 'hoàn thành', 'thanh cong', 'hoan thanh'],
                                        'cancelled' => ['cancelled', 'canceled', 'đã hủy', 'da huy'],
                                    ];

                                    $statusKey = null;
                                    foreach ($statusMap as $key => $aliases) {
                                        if (in_array($rawStatus, $aliases, true)) {
                                            $statusKey = $key;
                                            break;
                                        }
                                    }

                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'shipping' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipping' => 'Đang giao',
                                        'completed' => 'Thành công',
                                        'cancelled' => 'Đã hủy',
                                    ];

                                    $statusClass = $statusKey ? $statusClasses[$statusKey] : 'bg-gray-100 text-gray-800';
                                    $statusLabel = $statusKey ? $statusLabels[$statusKey] : esc($order['status']);
                                ?>
                                <span class="text-sm px-2 py-1 rounded <?= $statusClass ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600 text-center py-8">Chưa có đơn hàng nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleStatus() {
    if (!confirm('Bạn có chắc muốn thay đổi trạng thái tài khoản?')) return;
    
    $.ajax({
        url: '/admin/customers/toggle-status/<?= $customer['id'] ?>',
        method: 'POST',
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(response.message, 'error');
            }
        }
    });
}
</script>

<?= $this->endSection() ?>
