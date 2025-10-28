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
                    <a href="/profile/orders" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-shopping-bag mr-3"></i>Đơn hàng
                    </a>
                    <a href="/profile/payments" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-credit-card mr-3"></i>Lịch sử thanh toán
                    </a>
                    <a href="/profile/invoices" class="flex items-center px-4 py-3 bg-indigo-50 text-indigo-600 rounded-lg font-bold">
                        <i class="fas fa-file-invoice mr-3"></i>Hóa đơn
                    </a>
                    <a href="/logout" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg text-red-600">
                        <i class="fas fa-sign-out-alt mr-3"></i>Đăng xuất
                    </a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3">
            <h2 class="text-2xl font-bold mb-6">Hóa đơn của tôi</h2>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Số hóa đơn</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Mã đơn hàng</th>
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
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-600">
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
        </div>
    </div>
</div>

<?= $this->endSection() ?>
