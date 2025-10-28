<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Đánh giá chờ duyệt</h2>
    <a href="/admin/reviews" class="border border-indigo-600 text-indigo-600 px-6 py-3 rounded-lg font-bold hover:bg-indigo-50">
        <i class="fas fa-list mr-2"></i>Tất cả đánh giá
    </a>
</div>

<?php if (!empty($reviews)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($reviews as $review): ?>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-bold"><?= esc($review['full_name']) ?></p>
                        <div class="flex text-yellow-400">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'text-gray-300' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-box mr-1"></i><?= esc($review['product_name']) ?>
                </p>

                <p class="text-gray-700 mb-4 line-clamp-3"><?= esc($review['comment']) ?></p>

                <p class="text-xs text-gray-500 mb-4">
                    <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                </p>

                <div class="flex gap-2">
                    <button onclick="approveReview(<?= $review['id'] ?>)" 
                            class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-1"></i>Duyệt
                    </button>
                    <button onclick="deleteReview(<?= $review['id'] ?>)" 
                            class="px-4 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <i class="fas fa-check-circle text-6xl text-green-300 mb-4"></i>
        <h3 class="text-xl font-bold mb-2">Không có đánh giá chờ duyệt</h3>
        <p class="text-gray-600">Tất cả đánh giá đã được xử lý</p>
    </div>
<?php endif; ?>

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
