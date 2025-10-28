<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Giỏ hàng của bạn</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div id="cartItems" class="space-y-4">
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="bg-white rounded-xl shadow-lg p-6 flex gap-4" data-id="<?= $item['id'] ?>">
                            <img src="<?= esc($item['image']) ?>" alt="<?= esc($item['name']) ?>" 
                                 class="w-24 h-24 object-cover rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg"><?= esc($item['name']) ?></h3>
                                <p class="text-gray-600 text-sm">
                                    <?php if (!empty($item['size'])): ?>Kích thước: <?= esc($item['size']) ?><?php endif; ?>
                                    <?php if (!empty($item['color'])): ?> | Màu: <?= esc($item['color']) ?><?php endif; ?>
                                </p>
                                <p class="text-indigo-600 font-bold text-xl mt-2"><?= number_format($item['price']) ?>đ</p>
                                
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center">
                                        <button onclick="updateQuantity(<?= $item['id'] ?>, -1)" 
                                                class="px-3 py-1 border border-gray-300 rounded-l-lg hover:bg-gray-100">-</button>
                                        <input type="number" value="<?= $item['quantity'] ?>" min="1" 
                                               class="w-16 px-2 py-1 border-t border-b border-gray-300 text-center quantity-input"
                                               data-id="<?= $item['id'] ?>"
                                               onchange="updateQuantity(<?= $item['id'] ?>, 0)">
                                        <button onclick="updateQuantity(<?= $item['id'] ?>, 1)" 
                                                class="px-3 py-1 border border-gray-300 rounded-r-lg hover:bg-gray-100">+</button>
                                    </div>
                                    
                                    <div class="flex items-center gap-4">
                                        <span class="font-bold text-lg"><?= number_format($item['subtotal']) ?>đ</span>
                                        <button onclick="removeFromCart(<?= $item['id'] ?>)" 
                                                class="text-red-600 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Giỏ hàng trống</h3>
                        <p class="text-gray-600 mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                        <a href="/products" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                            Xem sản phẩm
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($cart_items)): ?>
                <div class="mt-4">
                    <button onclick="clearCart()" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-trash mr-2"></i>Xóa tất cả
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <?php if (!empty($cart_items)): ?>
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold mb-4">Tóm tắt đơn hàng</h3>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-bold" id="subtotal"><?= number_format($total) ?>đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-bold">Miễn phí</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between text-xl">
                            <span class="font-bold">Tổng cộng:</span>
                            <span class="font-bold text-indigo-600" id="total"><?= number_format($total) ?>đ</span>
                        </div>
                    </div>
                    
                    <a href="/checkout" class="block w-full bg-indigo-600 text-white py-3 rounded-lg font-bold text-center hover:bg-indigo-700">
                        Thanh toán
                    </a>
                    
                    <a href="/products" class="block w-full mt-3 border border-indigo-600 text-indigo-600 py-3 rounded-lg font-bold text-center hover:bg-indigo-50">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Clear Cart Modal -->
<div id="clearCartModal" <div id="clearCartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-start justify-center z-50 pt-[50px]">
    <div class="bg-white rounded-lg shadow-2xl p-4 max-w-md w-full mx-4 animate-fade-in min-h-[200px] flex flex-col" onclick="event.stopPropagation()">
        <div class="mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Xác nhận xóa giỏ hàng</h3>
        <p class="text-gray-600">Bạn có chắc muốn xóa tất cả sản phẩm trong giỏ hàng? Hành động này không thể hoàn tác.</p>
        <div class="flex gap-3 mt-6">
            <button onclick="closeClearCartModal()" class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                Hủy
            </button>
            <button onclick="confirmClearCart()" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">
                Xóa tất cả
            </button>
        </div>
    </div>
</div>

<!-- Remove Item Modal -->
<div id="removeItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-start justify-center z-50 pt-[50px]" onclick="closeRemoveModal()">
    <div class="bg-white rounded-lg shadow-2xl p-4 max-w-md w-full mx-4 animate-fade-in min-h-[200px] flex flex-col" onclick="event.stopPropagation()">
        <div class="mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Xác nhận xóa sản phẩm</h3>
        <p class="text-gray-600">Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?</p>
        <div class="flex gap-3 mt-6">
            <button onclick="closeRemoveModal()" class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                Hủy
            </button>
            <button onclick="confirmRemove()" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">
                Xóa
            </button>
        </div>
    </div>
</div>

<script>
function updateQuantity(productId, change) {
    const input = document.querySelector(`input[data-id="${productId}"]`);
    let quantity = parseInt(input.value);
    
    if (change !== 0) {
        quantity += change;
    }
    
    if (quantity < 1) return;
    
    $.ajax({
        url: '/cart/update',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                showToast(response.message, 'error');
            }
        }
    });
}

function removeFromCart(productId) {
    showRemoveModal(productId);
}

let currentRemoveProductId;

function showRemoveModal(productId) {
    currentRemoveProductId = productId;
    document.getElementById('removeItemModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    document.addEventListener('keydown', handleRemoveModalEscape);
}

function closeRemoveModal() {
    document.getElementById('removeItemModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    document.removeEventListener('keydown', handleRemoveModalEscape);
}

function handleRemoveModalEscape(event) {
    if (event.key === 'Escape') {
        closeRemoveModal();
    }
}

function confirmRemove() {
    closeRemoveModal();
    
    $.ajax({
        url: '/cart/remove',
        method: 'POST',
        data: { product_id: currentRemoveProductId },
        success: function(response) {
            if (response.success) {
                showTailwindToast(response.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showTailwindToast(response.message, 'error');
            }
        }
    });
}
function showTailwindToast(message, type = 'success') {
    const existingToast = document.getElementById('tailwind-toast');
    if (existingToast) {
        existingToast.remove();
    }

    const toastContainer = document.createElement('div');
    toastContainer.id = 'tailwind-toast';
    toastContainer.className = 'fixed top-4 right-4 z-50 animate-fade-in';

    const isSuccess = type === 'success';
    const bgColor = isSuccess ? 'bg-green-500' : 'bg-red-500';
    const borderColor = isSuccess ? 'border-green-500' : 'border-red-500';
    const icon = isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle';
    const textColor = isSuccess ? 'text-green-800' : 'text-red-800';

    toastContainer.innerHTML = `
        <div class="bg-white rounded-lg shadow-2xl px-6 py-4 flex items-center space-x-4 border-l-4 ${borderColor} min-w-[320px] max-w-md transform transition-all duration-300">
            <div class="flex-shrink-0">
                <i class="fas ${icon} ${isSuccess ? 'text-green-500' : 'text-red-500'} text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium ${textColor}">${message}</p>
            </div>
            <button onclick="closeTailwindToast()" class="flex-shrink-0 ml-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(toastContainer);

    setTimeout(() => {
        if (toastContainer.parentNode) {
            toastContainer.classList.add('animate-fade-out');
            setTimeout(() => toastContainer.remove(), 300);
        }
    }, 5000);
}
function closeTailwindToast() {
    const toast = document.getElementById('tailwind-toast');
    if (toast) {
        toast.classList.add('animate-fade-out');
        setTimeout(() => toast.remove(), 300);
    }
}

function clearCart() {
    showClearCartModal();
}

function showClearCartModal() {
    document.getElementById('clearCartModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeClearCartModal() {
    document.getElementById('clearCartModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function confirmClearCart() {
    closeClearCartModal();
    
    $.ajax({
        url: '/cart/clear',
        method: 'POST',
        success: function(response) {
            if (response.success) {
                showTailwindToast(response.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showTailwindToast(response.message, 'error');
            }
        }
    });
}
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-10px); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-in;
}
</style>

<?= $this->endSection() ?>
