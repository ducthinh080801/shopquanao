<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold">Quản lý khách hàng</h2>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Họ tên</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Số điện thoại</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tổng đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tổng chi tiêu</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Ngày tham gia</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?= $customer['id'] ?></td>
                            <td class="px-6 py-4">
                                <p class="font-bold"><?= esc($customer['full_name']) ?></p>
                            </td>
                            <td class="px-6 py-4"><?= esc($customer['email']) ?></td>
                            <td class="px-6 py-4"><?= esc($customer['phone']) ?></td>
                            <td class="px-6 py-4 font-bold"><?= $customer['order_count'] ?? 0 ?></td>
                            <td class="px-6 py-4 font-bold text-indigo-600"><?= number_format($customer['total_spent'] ?? 0) ?>đ</td>
                            <td class="px-6 py-4 text-sm"><?= date('d/m/Y', strtotime($customer['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <a href="/admin/customers/detail/<?= $customer['id'] ?>" 
                                   class="text-blue-600 hover:text-blue-700">
                                    <i class="fas fa-eye mr-1"></i>Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-600">
                            Chưa có khách hàng nào
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

<?= $this->endSection() ?>
