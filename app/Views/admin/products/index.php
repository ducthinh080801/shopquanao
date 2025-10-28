<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Danh sách sản phẩm</h2>
    <a href="/admin/products/create" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i>Thêm sản phẩm
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?= session()->getFlashdata('success') ?>', 'success');
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?= session()->getFlashdata('error') ?>', 'error');
        });
    </script>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Hình ảnh</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tên sản phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tồn kho</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Đã bán</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?= $product['id'] ?></td>
                            <td class="px-6 py-4">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= esc($product['image']) ?>" 
                                         alt="<?= esc($product['name']) ?>" 
                                         class="w-16 h-16 object-cover rounded"
                                         onerror="this.onerror=null; this.src='/placeholder/image';">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold"><?= esc($product['name']) ?></p>
                                <p class="text-sm text-gray-600"><?= esc($product['category_name'] ?? '') ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($product['discount_price'])): ?>
                                    <p class="text-gray-400 line-through text-sm"><?= number_format($product['price']) ?>đ</p>
                                    <p class="text-red-600 font-bold"><?= number_format($product['discount_price']) ?>đ</p>
                                <?php else: ?>
                                    <p class="font-bold"><?= number_format($product['price']) ?>đ</p>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="<?= $product['stock'] < 5 ? 'text-red-600 font-bold' : '' ?>">
                                    <?= $product['stock'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= $product['sold_count'] ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold <?= $product['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $product['is_active'] ? 'Hoạt động' : 'Ẩn' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="/admin/products/edit/<?= $product['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteProduct(<?= $product['id'] ?>)" 
                                            class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-600">
                            Chưa có sản phẩm nào
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
function deleteProduct(id) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
    
    $.ajax({
        url: '/admin/products/delete/' + id,
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
