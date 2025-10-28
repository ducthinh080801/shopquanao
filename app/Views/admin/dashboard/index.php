<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng doanh thu</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($stats['total_revenue'] ?? 0) ?>đ</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Đơn hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= $stats['total_orders'] ?? 0 ?></p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Sản phẩm</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= $stats['total_products'] ?? 0 ?></p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-box text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Khách hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= $stats['total_customers'] ?? 0 ?></p>
            </div>
            <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4">Doanh thu theo tháng</h3>
        <div style="height: 300px; max-height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4">Trạng thái đơn hàng</h3>
        <div style="height: 300px; max-height: 300px;">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Best Selling Products -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4">Sản phẩm bán chạy</h3>
        <div class="space-y-3">
            <?php if (!empty($best_sellers)): ?>
                <?php foreach ($best_sellers as $product): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <img src="<?= esc($product['image']) ?>" alt="" class="w-12 h-12 object-cover rounded" onerror="this.onerror=null; this.src='/placeholder/image';">
                            <div>
                                <p class="font-bold"><?= esc($product['name']) ?></p>
                                <p class="text-sm text-gray-600">Đã bán: <?= $product['sold_count'] ?></p>
                            </div>
                        </div>
                        <span class="font-bold text-indigo-600"><?= number_format($product['price']) ?>đ</span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600 text-center py-4">Chưa có dữ liệu</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
            Sản phẩm sắp hết hàng
        </h3>
        <div class="space-y-3">
            <?php if (!empty($low_stock)): ?>
                <?php foreach ($low_stock as $product): ?>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <img src="<?= esc($product['image']) ?>" alt="" class="w-12 h-12 object-cover rounded" onerror="this.onerror=null; this.src='/placeholder/image';">
                            <div>
                                <p class="font-bold"><?= esc($product['name']) ?></p>
                                <p class="text-sm text-red-600">Còn lại: <?= $product['stock'] ?></p>
                            </div>
                        </div>
                        <a href="/admin/products/restock/<?= $product['id'] ?>" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                            Nhập hàng
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600 text-center py-4">Tất cả sản phẩm còn đủ hàng</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($revenue_labels ?? []) ?>,
        datasets: [{
            label: 'Doanh thu (đ)',
            data: <?= json_encode($revenue_data ?? []) ?>,
            borderColor: 'rgb(99, 102, 241)',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Order Status Chart
const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Chờ xử lý', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            data: <?= json_encode($order_status_data ?? [0,0,0,0,0]) ?>,
            backgroundColor: [
                'rgb(234, 179, 8)',
                'rgb(59, 130, 246)',
                'rgb(168, 85, 247)',
                'rgb(34, 197, 94)',
                'rgb(239, 68, 68)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?= $this->endSection() ?>
