<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Chào mừng đến Shop Quần Áo</h1>
        <p class="text-xl mb-8">Thời trang chất lượng cao với giá cả hợp lý</p>
        <a href="/products" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 inline-block">
            Khám phá ngay <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

<!-- Featured Products -->
<section class="container mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold text-center mb-12">Sản phẩm nổi bật</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if (!empty($featured_products)): ?>
            <?php foreach ($featured_products as $product): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <a href="/products/<?= esc($product['slug']) ?>">
                        <img src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-64 object-cover" onerror="this.onerror=null; this.src='/placeholder/image';">
                    </a>
                    <div class="p-4">
                        <a href="/products/<?= esc($product['slug']) ?>" class="font-bold text-lg hover:text-indigo-600">
                            <?= esc($product['name']) ?>
                        </a>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <?php if (!empty($product['discount_price'])): ?>
                                    <span class="text-gray-400 line-through text-sm"><?= number_format($product['price']) ?>đ</span>
                                    <span class="text-red-600 font-bold text-xl ml-2"><?= number_format($product['discount_price']) ?>đ</span>
                                <?php else: ?>
                                    <span class="text-indigo-600 font-bold text-xl"><?= number_format($product['price']) ?>đ</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center text-yellow-400">
                                <i class="fas fa-star"></i>
                                <span class="ml-1 text-gray-600"><?= number_format($product['rating_avg'], 1) ?> (<?= $product['rating_count'] ?>)</span>
                            </div>
                            <button onclick="addToCart(<?= $product['id'] ?>)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Best Sellers -->
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Sản phẩm bán chạy</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($best_sellers)): ?>
                <?php foreach ($best_sellers as $product): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <a href="/products/<?= esc($product['slug']) ?>">
                            <img src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-64 object-cover" onerror="this.onerror=null; this.src='/placeholder/image';">
                        </a>
                        <div class="p-4">
                            <a href="/products/<?= esc($product['slug']) ?>" class="font-bold text-lg hover:text-indigo-600">
                                <?= esc($product['name']) ?>
                            </a>
                            <div class="mt-2">
                                <?php if (!empty($product['discount_price'])): ?>
                                    <span class="text-gray-400 line-through text-sm"><?= number_format($product['price']) ?>đ</span>
                                    <span class="text-red-600 font-bold text-xl ml-2"><?= number_format($product['discount_price']) ?>đ</span>
                                <?php else: ?>
                                    <span class="text-indigo-600 font-bold text-xl"><?= number_format($product['price']) ?>đ</span>
                                <?php endif; ?>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-fire text-red-500"></i> Đã bán: <?= $product['sold_count'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

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
