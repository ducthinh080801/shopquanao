<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <a href="/admin/products" class="text-indigo-600 hover:text-indigo-700">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6">Nhập hàng cho sản phẩm</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <form action="/admin/products/restock/<?= $product['id'] ?>" method="POST" id="restockForm">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block font-bold mb-2">Tên sản phẩm</label>
                <input type="text" readonly value="<?= esc($product['name']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
            </div>

            <div>
                <label class="block font-bold mb-2">Tồn kho hiện tại</label>
                <input type="text" readonly value="<?= $product['stock'] ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
            </div>

            <div>
                <label class="block font-bold mb-2">Số lượng nhập thêm *</label>
                <input type="number" name="quantity" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                       placeholder="Nhập số lượng">
            </div>

            <div>
                <label class="block font-bold mb-2">Tồn kho sau khi nhập</label>
                <input type="text" id="newStock" readonly value="<?= $product['stock'] ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="/admin/products" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>Nhập hàng
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.querySelector('input[name="quantity"]');
    const newStockInput = document.getElementById('newStock');
    const currentStock = <?= $product['stock'] ?>;

    quantityInput.addEventListener('input', function() {
        const quantity = parseInt(this.value) || 0;
        newStockInput.value = currentStock + quantity;
    });
});
</script>

<?= $this->endSection() ?>
