<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Kết quả tìm kiếm</h1>
        <p class="text-gray-600">Tìm thấy <span class="font-bold text-indigo-600"><?= count($products) ?></span> sản phẩm cho "<span class="font-bold"><?= esc($keyword) ?></span>"</p>
    </div>

    <?php if (!empty($products)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <a href="/products/<?= esc($product['slug']) ?>">
                        <img src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" 
                             class="w-full h-64 object-cover" onerror="this.onerror=null; this.src='/placeholder/image';">
                    </a>
                    <div class="p-4">
                        <a href="/products/<?= esc($product['slug']) ?>" 
                           class="font-bold text-lg hover:text-indigo-600 line-clamp-2">
                            <?= esc($product['name']) ?>
                        </a>
                        <div class="flex items-center mt-2">
                            <div class="flex text-yellow-400">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= round($product['rating_avg']) ? '' : 'text-gray-300' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="ml-2 text-gray-600 text-sm">(<?= $product['rating_count'] ?>)</span>
                        </div>
                        <div class="mt-3">
                            <?php if (!empty($product['discount_price'])): ?>
                                <span class="text-gray-400 line-through text-sm"><?= number_format($product['price']) ?>đ</span>
                                <span class="text-red-600 font-bold text-xl ml-2"><?= number_format($product['discount_price']) ?>đ</span>
                            <?php else: ?>
                                <span class="text-indigo-600 font-bold text-xl"><?= number_format($product['price']) ?>đ</span>
                            <?php endif; ?>
                        </div>
                        <button onclick="addToCart(<?= $product['id'] ?>)" 
                                class="w-full mt-4 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-cart-plus mr-2"></i>Thêm vào giỏ
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold mb-2">Không tìm thấy sản phẩm</h3>
            <p class="text-gray-600 mb-6">Thử tìm kiếm với từ khóa khác hoặc xem các sản phẩm bên dưới</p>
            <a href="/products" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                Xem tất cả sản phẩm
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function addToCart(productId) {
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: 1
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                updateCartCount();
            } else {
                showToast(response.message, 'error');
            }
        }
    });
}
</script>

<?= $this->endSection() ?>
