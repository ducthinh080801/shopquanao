<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold">Quản lý đơn hàng</h2>
</div>

<!-- Filter Tabs -->
<div class="flex gap-4 mb-6">
    <?php 
    $currentStatus = $_GET['status'] ?? 'all';
    $statusButtons = [
        'all' => 'Tất cả',
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao',
        'completed' => 'Hoàn thành'
    ];
    
    foreach ($statusButtons as $status => $label):
        $isActive = $currentStatus === $status;
        $buttonClass = $isActive 
            ? 'px-6 py-2 rounded-lg font-bold bg-indigo-600 text-white'
            : 'px-6 py-2 rounded-lg font-bold bg-gray-200 hover:bg-gray-300';
    ?>
        <button onclick="filterOrders('<?= $status ?>')" class="<?= $buttonClass ?>"><?= $label ?></button>
    <?php endforeach; ?>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Mã đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tổng tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Ngày đặt</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-bold text-indigo-600"><?= esc($order['order_number']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold"><?= esc($order['shipping_name']) ?></p>
                                <p class="text-sm text-gray-600"><?= esc($order['shipping_phone']) ?></p>
                            </td>
                            <td class="px-6 py-4 font-bold"><?= number_format($order['total_amount']) ?>đ</td>
                            <td class="px-6 py-4">
                                <select onchange="updateOrderStatus(this, <?= $order['id'] ?>, this.value)" 
                                        data-current="<?= $order['status'] ?>"
                                        class="px-3 py-1 rounded-lg text-sm font-bold border cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php
                                    $statusLower = strtolower($order['status']);
                                    echo match($statusLower) {
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                                        'shipped', 'shipping' => 'bg-purple-100 text-purple-800 border-purple-300',
                                        'delivered', 'delivered' => 'bg-green-100 text-green-800 border-green-300',
                                        'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                                        default => 'bg-gray-100 text-gray-800 border-gray-300'
                                    };
                                ?>">
                                    <option value="pending" <?= strtolower($order['status']) === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                    <option value="processing" <?= strtolower($order['status']) === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                    <option value="shipped" <?= in_array(strtolower($order['status']), ['shipped', 'shipped']) ? 'selected' : '' ?>>Đang giao</option>
                                    <option value="delivered" <?= in_array(strtolower($order['status']), ['delivered', 'delivered']) ? 'selected' : '' ?>>Hoàn thành</option>
                                    <option value="cancelled" <?= strtolower($order['status']) === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-sm"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <a href="/admin/orders/detail/<?= $order['id'] ?>" 
                                   class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-eye mr-1"></i>Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600">
                            Chưa có đơn hàng nào
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (isset($pager)): ?>
        <div class="px-6 py-4 border-t">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<script>
function filterOrders(status) {
    window.location.href = status === 'all' ? '/admin/orders' : '/admin/orders?status=' + status;
}

function updateOrderStatus(selectElement, orderId, newStatus) {
    const currentStatus = selectElement.getAttribute('data-current');
    
    if (currentStatus === newStatus) return;
    
    if (!confirm('Bạn có chắc muốn thay đổi trạng thái đơn hàng?')) {
        selectElement.value = currentStatus;
        return;
    }
    
    $.ajax({
        url: '/admin/orders/update-status',
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            <?= csrf_token() ?>: '<?= csrf_hash() ?>',
            order_id: orderId,
            status: newStatus
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                
                // Update element styling
                selectElement.setAttribute('data-current', newStatus);
                selectElement.className = 'px-3 py-1 rounded-lg text-sm font-bold border cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 ';
                
                const statusClasses = {
                    'pending': 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'processing': 'bg-blue-100 text-blue-800 border-blue-300',
                    'shipped': 'bg-purple-100 text-purple-800 border-purple-300',
                    'delivered': 'bg-green-100 text-green-800 border-green-300',
                    'cancelled': 'bg-red-100 text-red-800 border-red-300'
                };
                
                selectElement.className += statusClasses[newStatus] || 'bg-gray-100 text-gray-800 border-gray-300';
            } else {
                showToast(response.message, 'error');
                selectElement.value = currentStatus;
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
            showToast('Có lỗi xảy ra', 'error');
            selectElement.value = currentStatus;
        }
    });
}
</script>

<?= $this->endSection() ?>
