<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Danh sách hóa đơn</h2>
    <a href="/admin/orders" class="text-indigo-600 hover:text-indigo-700">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại đơn hàng
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Số hóa đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Mã đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Số tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Ngày phát hành</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($invoices)): ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-bold text-indigo-600"><?= esc($invoice['invoice_number']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono">#<?= esc($invoice['order_number']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold"><?= esc($invoice['customer_name']) ?></p>
                                <p class="text-sm text-gray-600"><?= esc($invoice['customer_email']) ?></p>
                            </td>
                            <td class="px-6 py-4 font-bold text-lg"><?= number_format($invoice['total_amount']) ?>đ</td>
                            <td class="px-6 py-4 text-sm"><?= date('d/m/Y H:i', strtotime($invoice['invoice_date'])) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="/orders/invoice/<?= esc($invoice['invoice_number']) ?>" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-700" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/orders/download-invoice/<?= esc($invoice['invoice_number']) ?>" 
                                       class="text-green-600 hover:text-green-700" title="Tải PDF">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600">
                            Chưa có hóa đơn nào
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
