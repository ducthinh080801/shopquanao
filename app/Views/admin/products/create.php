<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <a href="/admin/products" class="text-indigo-600 hover:text-indigo-700">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Thêm sản phẩm mới</h2>

    <form action="/admin/products/store" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold mb-2">Tên sản phẩm *</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Slug *</label>
                <input type="text" name="slug" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                       placeholder="vd: ao-thun-nam">
            </div>

            <div>
                <label class="block font-bold mb-2">Danh mục *</label>
                <select name="category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Chọn danh mục</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block font-bold mb-2">Giá *</label>
                <input type="number" name="price" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Giá giảm</label>
                <input type="number" name="discount_price" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block font-bold mb-2">Số lượng tồn kho *</label>
                <input type="number" name="stock" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="md:col-span-2">
                <label class="block font-bold mb-2">Mô tả</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div>
                <label class="block font-bold mb-2">Hình ảnh chính *</label>
                <input type="url" name="image" id="imageUrl" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                       placeholder="URL hình ảnh"
                       onchange="previewImage(this.value)">
                <div id="imagePreview" class="mt-3"></div>
            </div>

            <div>
                <label class="block font-bold mb-2">Kích thước (cách nhau bởi dấu phẩy)</label>
                <input type="text" name="sizes"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                       placeholder="S,M,L,XL">
            </div>

            <div>
                <label class="block font-bold mb-2">Màu sắc (cách nhau bởi dấu phẩy)</label>
                <input type="text" name="colors"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                       placeholder="Đen,Trắng,Xanh">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" class="mr-2">
                    <span class="font-bold">Sản phẩm nổi bật</span>
                </label>
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span class="font-bold">Kích hoạt</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i>Lưu sản phẩm
            </button>
            <a href="/admin/products" class="border border-gray-300 px-6 py-3 rounded-lg font-bold hover:bg-gray-50">
                Hủy
            </a>
        </div>
    </form>
</div>

<script>
// Auto generate slug from name
$('input[name="name"]').on('input', function() {
    const name = $(this).val();
    const slug = name.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    $('input[name="slug"]').val(slug);
});

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
