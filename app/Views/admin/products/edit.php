<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <a href="/admin/products" class="text-indigo-600 hover:text-indigo-700">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Chỉnh sửa sản phẩm</h2>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/admin/products/update/<?= $product['id'] ?>" method="POST" id="editProductForm">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold mb-2">Tên sản phẩm *</label>
                <input type="text" name="name" required value="<?= esc($product['name']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Slug *</label>
                <input type="text" name="slug" required value="<?= esc($product['slug']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Danh mục *</label>
                <select name="category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block font-bold mb-2">Giá *</label>
                <input type="number" name="price" required min="0" value="<?= $product['price'] ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Giá giảm</label>
                <input type="number" name="discount_price" min="0" value="<?= $product['discount_price'] ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Số lượng tồn kho *</label>
                <input type="number" name="stock" required min="0" value="<?= $product['stock'] ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="md:col-span-2">
                <label class="block font-bold mb-2">Mô tả</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"><?= esc($product['description']) ?></textarea>
            </div>

            <div class="md:col-span-2">
                <div>
                    <label class="block font-bold mb-2">Hình ảnh chính</label>
                    <input type="url" name="image" id="imageUrl" value="<?= esc($product['image']) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                           onchange="previewImage(this.value)"
                           onkeyup="previewImage(this.value)">
                    <div id="imagePreview" class="mt-3">
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?= esc($product['image']) ?>" 
                                 alt="Preview" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                                 onerror="this.onerror=null; this.src='/placeholder/image';">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-bold mb-2">Kích thước (cách nhau bởi dấu phẩy)</label>
                <input type="text" name="sizes" value="<?= esc($product['sizes']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Màu sắc (cách nhau bởi dấu phẩy)</label>
                <input type="text" name="colors" value="<?= esc($product['colors']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" <?= $product['is_featured'] ? 'checked' : '' ?> class="mr-2">
                    <span class="font-bold">Sản phẩm nổi bật</span>
                </label>
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?= $product['is_active'] ? 'checked' : '' ?> class="mr-2">
                    <span class="font-bold">Kích hoạt</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i>Cập nhật sản phẩm
            </button>
            <a href="/admin/products" class="border border-gray-300 px-6 py-3 rounded-lg font-bold hover:bg-gray-50">
                Hủy
            </a>
        </div>
    </form>
</div>

<script>
// Image preview function
function previewImage(url) {
    const previewDiv = document.getElementById('imagePreview');
    if (url) {
        previewDiv.innerHTML = `
            <img src="${url}" 
                 alt="Preview" 
                 class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                 onerror="this.onerror=null; this.src='/placeholder/image';">
        `;
    } else {
        previewDiv.innerHTML = '';
    }
}
</script>

<?= $this->endSection() ?>
