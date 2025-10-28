<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Thông tin tài khoản</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-indigo-600 text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-xl"><?= esc(session()->get('full_name')) ?></h3>
                    <p class="text-gray-600"><?= esc(session()->get('email')) ?></p>
                </div>

                <nav class="space-y-2">
                    <a href="/profile" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'profile' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-user mr-3"></i>Thông tin cá nhân
                    </a>
                    <a href="/profile/orders" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'orders' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-shopping-bag mr-3"></i>Đơn hàng
                    </a>
                    <a href="/profile/payments" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'payments' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-credit-card mr-3"></i>Lịch sử thanh toán
                    </a>
                    <a href="/profile/invoices" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'invoices' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-file-invoice mr-3"></i>Hóa đơn
                    </a>
                    <a href="/logout" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg text-red-600">
                        <i class="fas fa-sign-out-alt mr-3"></i>Đăng xuất
                    </a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold mb-6">Cập nhật thông tin</h2>
                
                <form id="updateProfileForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-2">Họ và tên *</label>
                            <input type="text" name="full_name" required
                                   value="<?= esc($user['full_name']) ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Email *</label>
                            <input type="email" name="email" required
                                   value="<?= esc($user['email']) ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Số điện thoại</label>
                            <input type="tel" name="phone"
                                   value="<?= esc($user['phone'] ?? '') ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Ngày sinh</label>
                            <input type="date" name="date_of_birth"
                                   value="<?= esc($user['date_of_birth'] ?? '') ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block font-bold mb-2">Địa chỉ</label>
                        <textarea name="address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"><?= esc($user['address'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Lưu thay đổi
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Đổi mật khẩu</h2>
                
                <form id="changePasswordForm">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Mật khẩu hiện tại *</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Mật khẩu mới *</label>
                        <input type="password" name="new_password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Xác nhận mật khẩu mới *</label>
                        <input type="password" name="confirm_password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700">
                        <i class="fas fa-lock mr-2"></i>Đổi mật khẩu
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Hóa đơn gần đây</h2>
                    <a href="/profile/invoices" class="text-indigo-600 hover:text-indigo-700 text-sm">
                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <?php if (!empty($invoices)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Số hóa đơn</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Mã đơn hàng</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Số tiền</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Ngày</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($invoices as $invoice): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-indigo-600 text-sm"><?= esc($invoice['invoice_number']) ?></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-mono text-sm">#<?= esc($invoice['order_number']) ?></span>
                                        </td>
                                        <td class="px-4 py-3 font-bold text-sm"><?= number_format($invoice['total_amount']) ?>đ</td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?></td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-2">
                                                <a href="/orders/invoice/<?= esc($invoice['invoice_number']) ?>" 
                                                   target="_blank"
                                                   class="text-blue-600 hover:text-blue-700" title="Xem">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                <a href="/orders/download-invoice/<?= esc($invoice['invoice_number']) ?>" 
                                                   class="text-green-600 hover:text-green-700" title="Tải PDF">
                                                    <i class="fas fa-download text-sm"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-file-invoice text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-600">Chưa có hóa đơn nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$('#updateProfileForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '/profile/update',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                showToast(response.message);
            } else {
                showToast(response.message, 'error');
            }
        }
    });
});

$('#changePasswordForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '/profile/change-password',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                $('#changePasswordForm')[0].reset();
            } else {
                showToast(response.message, 'error');
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
