<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-lg"><?= esc(session()->get('full_name')) ?></h3>
                    <p class="text-gray-600 text-sm"><?= esc(session()->get('email')) ?></p>
                </div>

                <nav class="space-y-2">
                    <a href="/profile" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-user mr-3"></i>Thông tin cá nhân
                    </a>
                    <a href="/profile/orders" class="flex items-center px-4 py-3 bg-indigo-50 text-indigo-600 rounded-lg font-bold">
                        <i class="fas fa-shopping-bag mr-3"></i>Đơn hàng
                    </a>
                    <a href="/profile/payments" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-credit-card mr-3"></i>Lịch sử thanh toán
                    </a>
                    <a href="/logout" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg text-red-600">
                        <i class="fas fa-sign-out-alt mr-3"></i>Đăng xuất
                    </a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3">
            <h2 class="text-2xl font-bold mb-6">Đơn hàng của tôi</h2>

            <div class="space-y-4">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="font-bold text-lg">Đơn hàng #<?= esc($order['order_number']) ?></h3>
                                    <p class="text-gray-600 text-sm"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                                </div>
                                <span class="px-4 py-2 rounded-lg font-bold <?php
                                    echo match($order['status']) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'shipping' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                ?>">
                                    <?php
                                        echo match($order['status']) {
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipping' => 'Đang giao',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                            default => $order['status']
                                        };
                                    ?>
                                </span>
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

            <?php if (isset($pager)): ?>
                <div class="mt-8">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
