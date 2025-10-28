<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Quản lý đánh giá</h2>
    <a href="/admin/reviews/pending" class="bg-yellow-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-yellow-600">
        <i class="fas fa-clock mr-2"></i>Chờ duyệt
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Sản phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Đánh giá</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Nhận xét</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Ngày</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?= $review['id'] ?></td>
                            <td class="px-6 py-4">
                                <p class="font-bold"><?= esc($review['product_name']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p><?= esc($review['full_name']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex text-yellow-400">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'text-gray-300' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-md">
                                <p class="truncate"><?= esc($review['comment']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold <?= $review['is_approved'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= $review['is_approved'] ? 'Đã duyệt' : 'Chờ duyệt' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm"><?= date('d/m/Y', strtotime($review['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <?php if (!$review['is_approved']): ?>
                                        <button onclick="approveReview(<?= $review['id'] ?>)" 
                                                class="text-green-600 hover:text-green-700" title="Duyệt">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php else: ?>
                                        <button onclick="hideReview(<?= $review['id'] ?>)" 
                                                class="text-yellow-600 hover:text-yellow-700" title="Ẩn">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button onclick="deleteReview(<?= $review['id'] ?>)" 
                                            class="text-red-600 hover:text-red-700" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-600">
                            Chưa có đánh giá nào
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
function approveReview(id) {
    $.ajax({
        url: '/admin/reviews/approve/' + id,
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

function hideReview(id) {
    $.ajax({
        url: '/admin/reviews/hide/' + id,
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

function deleteReview(id) {
    if (!confirm('Bạn có chắc muốn xóa đánh giá này?')) return;
    
    $.ajax({
        url: '/admin/reviews/delete/' + id,
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
