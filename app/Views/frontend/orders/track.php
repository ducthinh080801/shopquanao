<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-indigo-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">Tra cứu đơn hàng</h1>
            <p class="text-gray-600">Nhập mã đơn hàng để kiểm tra trạng thái</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <form id="trackForm" method="POST" action="/orders/track">
                <?= csrf_field() ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="mb-6">
                    <label class="block font-bold mb-2 text-lg">Mã đơn hàng</label>
                    <input type="text" name="order_number" required
                           placeholder="Ví dụ: ORD-20241027-ABC123"
                           class="w-full px-6 py-4 border border-gray-300 rounded-lg text-lg focus:ring-2 focus:ring-indigo-500">
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Mã đơn hàng được gửi qua email sau khi thanh toán thành công
                    </p>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>Tra cứu
                </button>
            </form>
        </div>

        <?php if (!empty($order)): ?>
            <!-- Order Found -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 <?= $order['status'] === 'completed' ? 'bg-green-100' : 'bg-blue-100' ?> rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas <?= $order['status'] === 'completed' ? 'fa-check text-green-600' : 'fa-clock text-blue-600' ?> text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Đơn hàng #<?= esc($order['order_number']) ?></h2>
                    <span class="px-6 py-2 rounded-lg font-bold <?php
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
                                'shipping' => 'Đang giao hàng',
                                'completed' => 'Đã giao hàng',
                                'cancelled' => 'Đã hủy',
                                default => $order['status']
                            };
                        ?>
                    </span>
                </div>

                <!-- Order Timeline -->
                <div class="mb-6">
                    <div class="flex items-center justify-between relative">
                        <div class="absolute left-0 right-0 top-5 h-1 bg-gray-200"></div>
                        <div class="absolute left-0 top-5 h-1 bg-indigo-600" style="width: <?php
                            echo match($order['status']) {
                                'pending' => '0%',
                                'processing' => '33%',
                                'shipping' => '66%',
                                'completed' => '100%',
                                default => '0%'
                            };
                        ?>"></div>
                        
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full <?= in_array($order['status'], ['pending', 'processing', 'shipping', 'completed']) ? 'bg-indigo-600' : 'bg-gray-200' ?> flex items-center justify-center mb-2">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <p class="text-xs font-bold">Đặt hàng</p>
                        </div>
                        
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full <?= in_array($order['status'], ['processing', 'shipping', 'completed']) ? 'bg-indigo-600' : 'bg-gray-200' ?> flex items-center justify-center mb-2">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <p class="text-xs font-bold">Xác nhận</p>
                        </div>
                        
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full <?= in_array($order['status'], ['shipping', 'completed']) ? 'bg-indigo-600' : 'bg-gray-200' ?> flex items-center justify-center mb-2">
                                <i class="fas fa-shipping-fast text-white"></i>
                            </div>
                            <p class="text-xs font-bold">Đang giao</p>
                        </div>
                        
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full <?= $order['status'] === 'completed' ? 'bg-green-600' : 'bg-gray-200' ?> flex items-center justify-center mb-2">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <p class="text-xs font-bold">Hoàn thành</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày đặt:</span>
                        <span class="font-bold"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng tiền:</span>
                        <span class="font-bold text-indigo-600 text-xl"><?= number_format($order['total_amount']) ?>đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Địa chỉ giao hàng:</span>
                        <span class="font-bold text-right"><?= esc($order['shipping_address']) ?></span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="/orders/detail/<?= esc($order['order_number']) ?>" 
                       class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700">
                        <i class="fas fa-eye mr-2"></i>Xem chi tiết
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
