<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<style>
    .filter-input:focus,
    .filter-input:focus-visible,
    .filter-input:active {
        border-color: #d1d5db !important;
        outline: none !important;
        box-shadow: none !important;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-96 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-xl font-bold mb-4">Lọc sản phẩm</h3>
                
                <form id="filterForm" method="GET" action="/products">
                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h4 class="font-bold mb-3 flex items-center">
                            <i class="fas fa-tags mr-2 text-indigo-600"></i>Danh mục
                        </h4>
                        <div class="relative">
                            <select name="category_id" class="filter-input appearance-none w-full px-4 py-3 border border-gray-300 rounded-lg bg-white hover:border-indigo-400 transition duration-200 ease-in-out shadow-sm cursor-pointer">
                                <option value="">Tất cả danh mục</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($category_id ?? '') == $cat['id'] ? 'selected' : '' ?> class="py-2">
                                            <?= esc($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Chọn danh mục sản phẩm
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-bold mb-3 flex items-center">
                            <i class="fas fa-dollar-sign mr-2 text-indigo-600"></i>Giá
                        </h4>
                        <div class="px-2">
                            <input type="hidden" name="min_price" id="min_price" value="<?= $min_price ?? 0 ?>">
                            <input type="hidden" name="max_price" id="max_price" value="<?= $max_price ?? 10000000 ?>">

                            <div class="flex justify-between items-center mb-4 text-sm font-semibold">
                                <span id="min_value" class="price-badge">₫<?= number_format($min_price ?? ($global_min_price ?? 0)) ?></span>
                                <span id="max_value" class="price-badge">₫<?= number_format($max_price ?? ($global_max_price ?? 10000000)) ?></span>
                            </div>

                            <div class="relative h-2 slider-track bg-gray-200 rounded-full">
                                <div id="progress" class="absolute h-2 bg-indigo-500 rounded-full"></div>
                                <input type="range" id="min_range" min="<?= $global_min_price ?? 0 ?>" max="<?= $global_max_price ?? 10000000 ?>" step="10000" value="<?= $min_price ?? ($global_min_price ?? 0) ?>"
                                       class="dual-range">
                                <input type="range" id="max_range" min="<?= $global_min_price ?? 0 ?>" max="<?= $global_max_price ?? 10000000 ?>" step="10000" value="<?= $max_price ?? ($global_max_price ?? 10000000) ?>"
                                       class="dual-range">
                            </div>
                        </div>
                        <div class="pt-5 mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Kéo để chọn khoảng giá
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-6">
                        <h4 class="font-bold mb-3 flex items-center">
                            <i class="fas fa-sort-amount-down mr-2 text-indigo-600"></i>Sắp xếp
                        </h4>
                        <div class="relative">
                            <select name="sort" class="filter-input appearance-none w-full px-4 py-3 border border-gray-300 rounded-lg bg-white hover:border-indigo-400 transition duration-200 ease-in-out shadow-sm cursor-pointer">
                                <option value="" class="py-2">Mặc định</option>
                                <option value="price_asc" <?= ($sort ?? '') == 'price_asc' ? 'selected' : '' ?> class="py-2">Giá thấp đến cao</option>
                                <option value="price_desc" <?= ($sort ?? '') == 'price_desc' ? 'selected' : '' ?> class="py-2">Giá cao đến thấp</option>
                                <option value="newest" <?= ($sort ?? '') == 'newest' ? 'selected' : '' ?> class="py-2">Mới nhất</option>
                                <option value="best_selling" <?= ($sort ?? '') == 'best_selling' ? 'selected' : '' ?> class="py-2">Bán chạy</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Chọn tiêu chí sắp xếp sản phẩm
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-filter mr-2"></i>Áp dụng
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">
                    <?php if (!empty($q)): ?>
                        Kết quả tìm kiếm<br>
                        <span class="text-lg font-normal text-gray-600">Tìm thấy <?= $total_results ?> sản phẩm cho "<?= esc($q) ?>"</span>
                    <?php else: ?>
                        Sản phẩm (<?= count($products ?? []) ?>)
                    <?php endif; ?>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                            $escapedName = esc($product['name']);
                            if (!empty($q)) {
                                $pattern = '/' . preg_quote($q, '/') . '/iu';
                                $highlightedName = preg_replace($pattern, '<span class="bg-yellow-200">$0</span>', $escapedName);
                            } else {
                                $highlightedName = $escapedName;
                            }
                        ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                            <a href="/products/<?= esc($product['slug']) ?>" title="<?= esc($product['name']) ?>">
                                <img src="<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" 
                                     class="w-full h-64 object-cover" onerror="this.onerror=null; this.src='/placeholder/image';">
                            </a>
                            <div class="p-4">
                                <a href="/products/<?= esc($product['slug']) ?>" 
                                   class="font-bold text-lg hover:text-indigo-600 line-clamp-2" title="<?= esc($product['name']) ?>">
                                    <?= $highlightedName ?>
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
                                <div class="flex justify-between items-center mt-4">
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-box mr-1"></i>Còn: <?= $product['stock'] ?>
                                    </span>
                                    <button onclick="addToCart(<?= $product['id'] ?>)" 
                                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 text-lg">Không tìm thấy sản phẩm nào</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager)): ?>
                <div class="mt-8">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.price-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 120px;
    padding: 0.35rem 0.75rem;
    border: 1px solid #4f46e5;
    border-radius: 9999px;
    background-color: #eef2ff;
    color: #312e81;
    font-weight: 600;
}

.slider-track {
    position: relative;
}

.slider-track #progress {
    top: 50%;
    transform: translateY(-50%);
}

.dual-range {
    position: absolute;
    top: -3px;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
    background: none;
    pointer-events: none;
    appearance: none;
    outline: none;
    height: 0;
}

.dual-range::-webkit-slider-thumb {
    pointer-events: auto;
    appearance: none;
    height: 18px;
    width: 18px;
    border-radius: 9999px;
    background: #4f46e5;
    border: 3px solid #fff;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.35);
    cursor: pointer;
}

.dual-range::-moz-range-thumb {
    pointer-events: auto;
    height: 18px;
    width: 18px;
    border-radius: 9999px;
    background: #4f46e5;
    border: 3px solid #fff;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.35);
    cursor: pointer;
}

.dual-range::-ms-thumb {
    pointer-events: auto;
    height: 18px;
    width: 18px;
    border-radius: 9999px;
    background: #4f46e5;
    border: 3px solid #fff;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.35);
    cursor: pointer;
}

.dual-range::-webkit-slider-runnable-track {
    height: 2px;
}

.dual-range::-moz-range-track {
    height: 2px;
    background: transparent;
}

.dual-range::-ms-track {
    height: 2px;
    background: transparent;
    border-color: transparent;
    color: transparent;
}
</style>

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

// Dual Range Slider Logic
document.addEventListener('DOMContentLoaded', function() {
    const minRange = document.getElementById('min_range');
    const maxRange = document.getElementById('max_range');
    const progress = document.getElementById('progress');
    const minValue = document.getElementById('min_value');
    const maxValue = document.getElementById('max_value');
    const minPrice = document.getElementById('min_price');
    const maxPrice = document.getElementById('max_price');

    const globalMin = <?= $global_min_price ?? 0 ?>;
    const globalMax = <?= $global_max_price ?? 10000000 ?>;

    // Set z-index for proper layering
    minRange.style.zIndex = 3;
    maxRange.style.zIndex = 4;

    function updateSlider() {
        let minVal = parseInt(minRange.value);
        let maxVal = parseInt(maxRange.value);

        // Prevent min from going above max
        if (minVal > maxVal) {
            minVal = maxVal;
            minRange.value = minVal;
        }

        // Prevent max from going below min
        if (maxVal < minVal) {
            maxVal = minVal;
            maxRange.value = maxVal;
        }

        const percent1 = ((minVal - globalMin) / (globalMax - globalMin)) * 100;
        const percent2 = ((maxVal - globalMin) / (globalMax - globalMin)) * 100;

        if (percent2 >= 99.9) {
            progress.style.left = '0%';
            progress.style.width = '100%';
        } else {
            progress.style.left = percent1 + '%';
            progress.style.width = (percent2 - percent1) + '%';
        }

        minValue.textContent = '₫' + minVal.toLocaleString();
        maxValue.textContent = '₫' + maxVal.toLocaleString();

        minPrice.value = minVal;
        maxPrice.value = maxVal;
    }

    minRange.addEventListener('input', updateSlider);
    maxRange.addEventListener('input', updateSlider);

    updateSlider(); // Initial update
});
</script>

<?= $this->endSection() ?>
