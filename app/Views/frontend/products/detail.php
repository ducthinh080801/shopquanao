<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-gray-600">
        <a href="/" class="hover:text-indigo-600">Trang chủ</a> / 
        <a href="/products" class="hover:text-indigo-600">Sản phẩm</a> / 
        <span class="text-gray-800"><?= esc($product['name']) ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-4">
                <img id="mainImage" src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" 
                     class="w-full h-96 object-cover">
            </div>
            <?php if (!empty($product['images'])): ?>
                <?php $images = json_decode($product['images'], true); ?>
                <?php if (is_array($images)): ?>
                    <div class="grid grid-cols-4 gap-2">
                        <?php foreach ($images as $img): ?>
                            <img src="<?= esc($img) ?>" alt="Product" 
                                 onclick="document.getElementById('mainImage').src='<?= esc($img) ?>'"
                                 class="w-full h-24 object-cover rounded-lg cursor-pointer hover:ring-2 hover:ring-indigo-500">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-3xl font-bold mb-4"><?= esc($product['name']) ?></h1>
            
            <!-- Rating -->
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400 text-lg">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?= $i <= round($product['rating_avg']) ? '' : 'text-gray-300' ?>"></i>
                    <?php endfor; ?>
                </div>
                <span class="ml-2 text-gray-600"><?= number_format($product['rating_avg'], 1) ?> (<?= $product['rating_count'] ?> đánh giá)</span>
                <span class="ml-4 text-gray-600">| Đã bán: <?= $product['sold_count'] ?></span>
            </div>

            <!-- Price -->
            <div class="mb-6">
                <?php if (!empty($product['discount_price'])): ?>
                    <div class="flex items-center">
                        <span class="text-gray-400 line-through text-2xl"><?= number_format($product['price']) ?>đ</span>
                        <span class="text-red-600 font-bold text-4xl ml-4"><?= number_format($product['discount_price']) ?>đ</span>
                        <span class="ml-4 bg-red-100 text-red-600 px-3 py-1 rounded-lg">
                            -<?= round((1 - $product['discount_price'] / $product['price']) * 100) ?>%
                        </span>
                    </div>
                <?php else: ?>
                    <span class="text-indigo-600 font-bold text-4xl"><?= number_format($product['price']) ?>đ</span>
                <?php endif; ?>
            </div>

            <form id="addToCartForm">
                <!-- Size Selection -->
                <?php if (!empty($product['sizes'])): ?>
                    <?php $sizes = json_decode($product['sizes'], true); ?>
                    <?php if (is_array($sizes)): ?>
                        <div class="mb-4">
                            <label class="block font-bold mb-2">Kích thước:</label>
                            <div class="flex gap-2">
                                <?php foreach ($sizes as $size): ?>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="size" value="<?= esc($size) ?>" class="hidden peer" required>
                                        <div class="px-4 py-2 border border-gray-300 rounded-lg peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 hover:border-indigo-400">
                                            <?= esc($size) ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Color Selection -->
                <?php if (!empty($product['colors'])): ?>
                    <?php $colors = json_decode($product['colors'], true); ?>
                    <?php if (is_array($colors)): ?>
                        <div class="mb-4">
                            <label class="block font-bold mb-2">Màu sắc:</label>
                            <div class="flex gap-2">
                                <?php foreach ($colors as $color): ?>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color" value="<?= esc($color) ?>" class="hidden peer" required>
                                        <div class="px-4 py-2 border border-gray-300 rounded-lg peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 hover:border-indigo-400">
                                            <?= esc($color) ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Quantity -->
                <div class="mb-6">
                    <label class="block font-bold mb-2">Số lượng:</label>
                    <div class="flex items-center">
                        <button type="button" onclick="decreaseQty()" class="px-4 py-2 border border-gray-300 rounded-l-lg hover:bg-gray-100">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" 
                               class="w-20 px-4 py-2 border-t border-b border-gray-300 text-center">
                        <button type="button" onclick="increaseQty()" class="px-4 py-2 border border-gray-300 rounded-r-lg hover:bg-gray-100">+</button>
                        <span class="ml-4 text-gray-600">Còn lại: <?= $product['stock'] ?></span>
                    </div>
                </div>

                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                
                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700">
                        <i class="fas fa-cart-plus mr-2"></i>Thêm vào giỏ hàng
                    </button>
                    <button type="button" onclick="buyNow()" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700">
                        <i class="fas fa-bolt mr-2"></i>Mua ngay
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Description -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
        <h2 class="text-2xl font-bold mb-4">Mô tả sản phẩm</h2>
        <div class="prose max-w-none">
            <?= nl2br(esc($product['description'])) ?>
        </div>
    </div>

    <!-- Reviews -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
        <h2 class="text-2xl font-bold mb-6">Đánh giá sản phẩm</h2>
        
        <?php if (session()->get('isLoggedIn')): ?>
            <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                <h3 class="font-bold mb-4">Viết đánh giá của bạn</h3>
                <form id="reviewForm">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Đánh giá:</label>
                        <div class="flex gap-2" id="starRating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="far fa-star cursor-pointer text-3xl text-gray-400 hover:text-yellow-300 transition" 
                                   data-rating="<?= $i ?>"
                                   onclick="setRating(<?= $i ?>)"></i>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Nhận xét:</label>
                        <textarea name="comment" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required></textarea>
                    </div>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Gửi đánh giá</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Review List -->
        <div class="space-y-6">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="border-b pb-6">
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="font-bold"><?= esc($review['full_name']) ?></p>
                                <div class="flex text-yellow-400 text-sm">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'text-gray-300' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <span class="ml-auto text-sm text-gray-500"><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                        </div>
                        <p class="text-gray-700"><?= esc($review['comment']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600 text-center py-8">Chưa có đánh giá nào</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function decreaseQty() {
    const qty = document.getElementById('quantity');
    if (qty.value > 1) qty.value = parseInt(qty.value) - 1;
}

function increaseQty() {
    const qty = document.getElementById('quantity');
    const max = parseInt(qty.getAttribute('max'));
    if (qty.value < max) qty.value = parseInt(qty.value) + 1;
}

$('#addToCartForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                updateCartCount();
            } else {
                showToast(response.message, 'error');
            }
        }
    });
});

// Rating stars function
function setRating(rating) {
    // Set hidden input value
    document.getElementById('ratingValue').value = rating;
    
    // Get all stars
    const stars = document.querySelectorAll('#starRating i');
    
    // Loop through all stars and fill/unfill based on rating
    stars.forEach((star, index) => {
        if (index < rating) {
            // Fill star with yellow
            star.classList.remove('far', 'text-gray-400');
            star.classList.add('fas', 'text-yellow-400');
        } else {
            // Unfill star
            star.classList.remove('fas', 'text-yellow-400');
            star.classList.add('far', 'text-gray-400');
        }
    });
}

$('#reviewForm').submit(function(e) {
    e.preventDefault();
    
    // Check if rating is selected
    if (!document.getElementById('ratingValue').value) {
        showToast('Vui lòng chọn đánh giá sao', 'error');
        return;
    }
    
    $.ajax({
        url: '/products/review',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                $('#reviewForm')[0].reset();
                // Reset stars
                setRating(0);
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(response.message, 'error');
            }
        }
    });
});

function buyNow() {
    const form = $('#addToCartForm');
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success) {
                window.location.href = '/checkout';
            } else {
                showToast(response.message, 'error');
            }
        }
    });
}
</script>

<?= $this->endSection() ?>
