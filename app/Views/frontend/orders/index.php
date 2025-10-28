<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Đơn hàng của tôi</h1>

    <div class="space-y-4">
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-lg">Đơn hàng #<?= esc($order['order_number']) ?></h3>
                            <p class="text-gray-600 text-sm"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                        </div>
                        <?php
                            $rawStatus = mb_strtolower(trim($order['status'] ?? ''), 'UTF-8');
                            $statusMap = [
                                'pending' => ['pending', 'chờ xử lý', 'cho xu ly'],
                                'processing' => ['processing', 'đang xử lý', 'dang xu ly'],
                                'shipped' => ['shipped', 'đang giao', 'dang giao'],
                                'delivered' => ['delivered', 'thành công', 'hoàn thành', 'thanh cong', 'hoan thanh'],
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
                        <div class="text-right">
                            <span class="inline-block px-4 py-2 rounded-lg font-bold <?= $statusClass ?>">
                                <?= $statusLabel ?>
                            </span>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600">Tổng tiền:</p>
                                <p class="text-2xl font-bold text-indigo-600"><?= number_format($order['total_amount']) ?>đ</p>
                            </div>
                            <div class="space-x-3">
                                <a href="/orders/detail/<?= $order['order_number'] ?>" 
                                   class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                                    <i class="fas fa-eye mr-2"></i>Chi tiết
                                </a>
                                <?php if ($order['status'] === 'delivered'): ?>
                                    <a href="/orders/invoice/<?= $order['order_number'] ?>" 
                                       class="inline-block border border-indigo-600 text-indigo-600 px-6 py-2 rounded-lg hover:bg-indigo-50">
                                        <i class="fas fa-file-invoice mr-2"></i>Hóa đơn
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Chưa có đơn hàng nào</h3>
                <p class="text-gray-600 mb-4">Bạn chưa đặt đơn hàng nào</p>
                <a href="/products" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    Mua sắm ngay
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($pager)): ?>
        <div class="mt-8">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
