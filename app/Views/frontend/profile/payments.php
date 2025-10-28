<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="font-bold text-lg"><?= esc(session()->get('full_name')) ?></h3>
                    <p class="text-gray-600 text-sm"><?= esc(session()->get('email')) ?></p>
                </div>

                <nav class="space-y-2">
                    <a href="/profile" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'profile' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-user mr-3"></i>Thông tin cá nhân
                    </a>
                    <a href="/profile/orders" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'orders' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-shopping-bag mr-3"></i>Đơn hàng
                    </a>
                    <a href="/profile/payments" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'payments' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-credit-card mr-3"></i>Lịch sử thanh toán
                    </a>
                    <a href="/profile/invoices" class="flex items-center px-4 py-3 <?= ($tab ?? '') === 'invoices' ? 'bg-indigo-50 text-indigo-600 rounded-lg font-bold' : 'hover:bg-gray-50 rounded-lg' ?>">
                        <i class="fas fa-file-invoice mr-3"></i>Hóa đơn
                    </a>
                    <a href="/logout" class="flex items-center px-4 py-3 hover:bg-gray-50 rounded-lg text-red-600">
                        <i class="fas fa-sign-out-alt mr-3"></i>Đăng xuất
                    </a>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="lg:col-span-3">
            <!-- Cards and Summary Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Thẻ & Tổng quan</h2>
                    <button onclick="openAddCardModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i>Thêm thẻ mới
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 min-h-[250px]">
                    <!-- Saved Cards -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Thẻ đã lưu</h3>
                        <div id="savedCards" class="min-h-[250px]">
                            <?php if (!empty($user['stripe_card_id'])): ?>
                                <div id="stripeCard" class="border-2 border-yellow-400 rounded-lg p-6 bg-gradient-to-br from-gray-900 to-black text-white h-[250px] relative overflow-hidden shadow-lg">
                                    <div class="absolute inset-0 opacity-20">
                                        <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400 rounded-full -mr-16 -mt-16"></div>
                                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-yellow-400 rounded-full -ml-12 -mb-12"></div>
                                    </div>

                                    <div class="relative h-full flex flex-col justify-between z-10">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center">
                                                <i class="fab fa-cc-<?= strtolower($user['stripe_brand'] ?? 'visa') ?> text-3xl text-yellow-400 opacity-90"></i>
                                            </div>
                                            <button onclick="removeCard()" class="text-yellow-400 hover:text-red-300 p-1 rounded transition-colors">
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                        </div>

                                        <div class="text-center">
                                            <p class="font-mono text-2xl tracking-widest mb-4 text-yellow-100">
                                                **** **** **** <?= esc($user['stripe_last4']) ?>
                                            </p>
                                        </div>

                                        <div class="flex justify-between items-end">
                                            <div>
                                                <p class="text-xs text-yellow-200 opacity-75 uppercase tracking-wider mb-1">Card Holder</p>
                                                <p class="font-semibold text-white text-sm"><?= esc($user['stripe_card_name']) ?></p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-yellow-200 opacity-75 uppercase tracking-wider mb-1">Expires</p>
                                                <p class="font-semibold text-white text-sm">
                                                    <?= sprintf('%02d/%02d', $user['stripe_exp_month'], $user['stripe_exp_year'] % 100) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($user['momo_phone'])): ?>
                                <div id="momoCard" class="border-2 border-pink-200 rounded-lg p-6 bg-gradient-to-br from-pink-50 to-white h-[250px] relative overflow-hidden <?php echo (!empty($user['stripe_card_id'])) ? 'hidden' : ''; ?>">
                                    <div class="absolute inset-0 opacity-10">
                                        <div class="absolute top-4 right-4 w-20 h-20 bg-pink-300 rounded-full"></div>
                                        <div class="absolute bottom-4 left-4 w-16 h-16 bg-pink-300 rounded-full"></div>
                                    </div>

                                    <div class="relative h-full flex flex-col justify-center">
                                        <!-- Delete button positioned absolutely -->
                                        <div class="absolute top-4 right-4">
                                            <button onclick="removeCard()" class="text-red-600 hover:text-red-300 p-1 rounded transition-colors">
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                        </div>

                                        <!-- Centered content -->
                                        <div class="text-center">
                                            <i class="fas fa-mobile-alt text-6xl text-pink-600 mb-4"></i>
                                            <p class="font-bold text-lg text-gray-800 mb-2">Ví MoMo</p>
                                            <p class="font-mono text-xl text-gray-700 mb-4">
                                                *** *** **** <?= substr($user['momo_phone'], -3) ?>
                                            </p>
                                            <div>
                                                <p class="text-xs text-gray-600 uppercase tracking-wide">Chủ tài khoản</p>
                                                <p class="font-semibold text-sm text-gray-800"><?= esc($user['momo_name']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (empty($user['stripe_card_id']) && empty($user['momo_phone'])): ?>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 flex items-center justify-center text-gray-400 h-full">
                                    <div class="text-center">
                                        <i class="fas fa-credit-card text-4xl mb-2"></i>
                                        <p>Chưa có thẻ nào được lưu</p>
                                        <button onclick="openAddCardModal()" class="mt-2 text-indigo-600 hover:text-indigo-700">
                                            Thêm thẻ đầu tiên
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($user['stripe_card_id']) && !empty($user['momo_phone'])): ?>
                            <!-- Payment Method Selector -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn phương thức hiển thị:</label>
                                <div class="flex gap-2">
                                    <button id="showStripe" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 active">Thẻ tín dụng</button>
                                    <button id="showMoMo" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Ví MoMo</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Payment Summary -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Tổng quan thanh toán</h3>
                        <div class="border-2 border-yellow-400 rounded-lg p-6 bg-gradient-to-br from-gray-900 to-black text-white h-[250px] relative overflow-hidden shadow-lg">
                            <!-- Subtle gold background pattern -->
                            <div class="absolute inset-0 opacity-20">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400 rounded-full -mr-16 -mt-16"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-yellow-400 rounded-full -ml-12 -mb-12"></div>
                            </div>

                            <div class="relative h-full flex flex-col justify-center z-10">
                                <!-- Chart section -->
                                <div class="flex-1 flex items-center justify-center relative">
                                    <canvas id="paymentChart" style="max-width: 100%; height: 150px;"></canvas>
                                    <!-- Text overlay on chart -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-yellow-100 mb-1"><?= number_format($total_paid) ?>đ</div>
                                            <div class="text-xs text-yellow-200">Tổng thanh toán</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <h2 class="text-2xl font-bold mb-6">Lịch sử thanh toán</h2>

            <div class="space-y-4">
                <?php if (!empty($payments)): ?>
                    <?php foreach ($payments as $payment): ?>
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="font-bold text-lg">Đơn hàng #<?= esc($payment['order_number']) ?></h3>
                                    <p class="text-gray-600 text-sm"><?= date('d/m/Y H:i', strtotime($payment['created_at'])) ?></p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        Mã giao dịch: <span class="font-mono"><?= esc($payment['transaction_id']) ?></span>
                                    </p>
                                </div>
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-bold">
                                    <?= ucfirst($payment['status']) ?>
                                </span>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600">Số tiền:</p>
                                        <p class="text-2xl font-bold text-indigo-600"><?= number_format($payment['amount']) ?>đ</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-sm">Phương thức:</p>
                                        <p class="font-bold"><i class="fab fa-cc-stripe mr-1"></i><?= ucfirst($payment['payment_method']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-credit-card text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Chưa có lịch sử thanh toán</h3>
                        <p class="text-gray-600">Bạn chưa có giao dịch thanh toán nào</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($pager)): ?>
                <div class="mt-8">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Confirm Remove Card Modal -->
<div id="removeCardModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Xác nhận xóa thẻ</h3>
                <p class="text-gray-600 mb-6">Bạn có chắc muốn xóa phương thức thanh toán này? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="confirmRemoveCard()" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Xóa
                </button>
                <button onclick="closeRemoveCardModal()" class="flex-1 border border-gray-300 rounded-lg font-bold hover:bg-gray-50 py-3">
                    Hủy
                </button>
            </div>
        </div>
    </div>
</div>
<div id="addCardModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Thêm phương thức thanh toán</h3>
                <button onclick="closeAddCardModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="addCardForm">
                <div class="mb-6">
                    <label class="block font-bold mb-4 text-gray-700">Chọn phương thức thanh toán</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative h-full">
                            <input type="radio" name="payment_method" value="stripe" checked class="sr-only peer">
                            <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all h-full flex flex-col justify-center">
                                <div class="text-center">
                                    <i class="fab fa-cc-stripe text-3xl text-indigo-600 mb-2"></i>
                                    <div class="font-semibold text-gray-800 mb-1">Thẻ tín dụng</div>
                                    <div class="text-sm text-gray-600">Visa, Mastercard, etc.</div>
                                </div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:bg-indigo-500 peer-checked:border-indigo-500 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full peer-checked:block hidden"></div>
                                </div>
                            </div>
                        </label>
                        <label class="relative h-full">
                            <input type="radio" name="payment_method" value="momo" class="sr-only peer">
                            <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all h-full flex flex-col justify-center">
                                <div class="text-center">
                                    <i class="fas fa-mobile-alt text-3xl text-pink-600 mb-2"></i>
                                    <div class="font-semibold text-gray-800 mb-1">Ví MoMo</div>
                                    <div class="text-sm text-gray-600">Thanh toán nhanh</div>
                                </div>
                                <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:bg-pink-500 peer-checked:border-pink-500 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full peer-checked:block hidden"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="stripeFields">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Số thẻ *</label>
                        <div id="card-number" class="p-3 border border-gray-300 rounded-lg"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-bold mb-2">Ngày hết hạn *</label>
                            <div id="card-expiry" class="p-3 border border-gray-300 rounded-lg"></div>
                        </div>
                        <div>
                            <label class="block font-bold mb-2">CVC *</label>
                            <div id="card-cvc" class="p-3 border border-gray-300 rounded-lg"></div>
                        </div>
                    </div>
                </div>

                <div id="momoFields" class="hidden">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Số điện thoại MoMo *</label>
                        <input type="tel" id="momo-phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500"
                               placeholder="Nhập số điện thoại MoMo">
                        <div class="mt-2 text-xs text-gray-500">
                            <strong>Test numbers:</strong> 0912345678 (success)
                        </div>
                    </div>
                    <div class="mb-4 p-4 bg-pink-50 border border-pink-200 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-info-circle text-pink-600 mr-1"></i>
                            <strong>Test Mode:</strong> Sử dụng số test để mô phỏng thanh toán MoMo.
                        </p>
                        <p class="text-sm text-gray-600">
                            Bạn sẽ được chuyển hướng đến ứng dụng MoMo để xác nhận liên kết tài khoản (giả lập).
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="card-holder-name" class="block font-bold mb-2">Tên chủ thẻ *</label>
                    <input type="text" id="card-holder-name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                           placeholder="Tên chủ thẻ">
                </div>

                <div id="card-errors" class="text-red-600 text-sm mb-4"></div>

                <div class="flex gap-3">
                    <button type="submit" id="submitCardBtn"
                            class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Lưu thẻ
                    </button>
                    <button type="button" onclick="closeAddCardModal()"
                            class="px-6 border border-gray-300 rounded-lg font-bold hover:bg-gray-50">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
// Use exact Stripe key from .env
const stripe = Stripe('pk_test_Fuk1wQjIPkQy0rD7Ptmh09x8');
const elements = stripe.elements();

const cardNumber = elements.create('cardNumber');
const cardExpiry = elements.create('cardExpiry');
const cardCvc = elements.create('cardCvc');

let savedCardsData = [];

function openAddCardModal() {
    document.getElementById('addCardModal').classList.remove('hidden');
    if (!cardNumber._mounted) {
        cardNumber.mount('#card-number');
        cardExpiry.mount('#card-expiry');
        cardCvc.mount('#card-cvc');
    }
}

function closeAddCardModal() {
    document.getElementById('addCardModal').classList.add('hidden');
    document.getElementById('addCardForm').reset();
    document.getElementById('card-errors').textContent = '';
}

// Handle payment method change
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const stripeFields = document.getElementById('stripeFields');
        const momoFields = document.getElementById('momoFields');
        const submitBtn = document.getElementById('submitCardBtn');
        const cardHolderLabel = document.querySelector('label[for="card-holder-name"]');
        
        // Get form elements
        const cardNumber = document.querySelector('#card-number');
        const cardExpiry = document.querySelector('#card-expiry');
        const cardCvc = document.querySelector('#card-cvc');
        const momoPhone = document.getElementById('momo-phone');
        
        if (this.value === 'stripe') {
            stripeFields.classList.remove('hidden');
            momoFields.classList.add('hidden');
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Lưu thẻ';
            cardHolderLabel.textContent = 'Tên trên thẻ *';
            
            // Make Stripe fields required
            if (cardNumber) cardNumber.required = true;
            if (cardExpiry) cardExpiry.required = true;
            if (cardCvc) cardCvc.required = true;
            if (momoPhone) momoPhone.required = false;
            
            // Initialize Stripe elements if not already
            if (!window.cardNumber || !window.cardNumber._mounted) {
                if (window.cardNumber) window.cardNumber.mount('#card-number');
                if (window.cardExpiry) window.cardExpiry.mount('#card-expiry');
                if (window.cardCvc) window.cardCvc.mount('#card-cvc');
            }
        } else {
            stripeFields.classList.add('hidden');
            momoFields.classList.remove('hidden');
            submitBtn.innerHTML = '<i class="fas fa-mobile-alt mr-2"></i>Liên kết MoMo';
            cardHolderLabel.textContent = 'Tên chủ tài khoản *';
            
            // Make MoMo field required, remove from Stripe
            if (cardNumber) cardNumber.required = false;
            if (cardExpiry) cardExpiry.required = false;
            if (cardCvc) cardCvc.required = false;
            if (momoPhone) momoPhone.required = true;
        }
    });
});
[cardNumber, cardExpiry, cardCvc].forEach(element => {
    element.on('change', (event) => {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
});

// Submit card form
document.getElementById('addCardForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const submitBtn = document.getElementById('submitCardBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = paymentMethod === 'stripe' ? 
        '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...' : 
        '<i class="fas fa-spinner fa-spin mr-2"></i>Đang liên kết...';
    
    if (paymentMethod === 'stripe') {
        const {token, error} = await stripe.createToken(cardNumber, {
            name: document.getElementById('card-holder-name').value
        });
        
        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Lưu thẻ';
            return;
        }
        
        // Save Stripe card
        $.ajax({
            url: '/profile/addCard',
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                stripe_token: token.id,
                card_name: document.getElementById('card-holder-name').value,
                payment_method: 'stripe'
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message);
                    closeAddCardModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(response.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Lưu thẻ';
                }
            },
            error: function() {
                showToast('Có lỗi xảy ra', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Lưu thẻ';
            }
        });
    } else {
        // Handle MoMo linking
        const momoPhone = document.getElementById('momo-phone').value;
        const cardHolderName = document.getElementById('card-holder-name').value;
        
        // Test numbers for simulation
        const testNumbers = {
            '0912345678': 'success',
            '0987654321': 'fail'
        };
        
        const isTestNumber = testNumbers[momoPhone];
        
        // Simulate MoMo linking process
        setTimeout(() => {
            if (isTestNumber === 'success') {
                // Mock successful linking
                $.ajax({
                    url: '/profile/addCard',
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                        momo_phone: momoPhone,
                        card_name: cardHolderName,
                        payment_method: 'momo'
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('Liên kết MoMo thành công!');
                            closeAddCardModal();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showToast(response.message || 'Liên kết thất bại', 'error');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-mobile-alt mr-2"></i>Liên kết MoMo';
                        }
                    },
                    error: function() {
                        showToast('Có lỗi xảy ra khi liên kết MoMo', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-mobile-alt mr-2"></i>Liên kết MoMo';
                    }
                });
            } else if (isTestNumber === 'fail') {
                showToast('Số điện thoại không hợp lệ hoặc không có tài khoản MoMo', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-mobile-alt mr-2"></i>Liên kết MoMo';
            } else {
                // For non-test numbers, simulate pending verification
                showToast('Đang chuyển hướng đến MoMo để xác nhận...', 'info');
                setTimeout(() => {
                    showToast('Liên kết MoMo thành công! (Mô phỏng)', 'success');
                    closeAddCardModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }, 3000);
            }
        }, 2000); // Simulate processing time
    }
});

function removeCard() {
    document.getElementById('removeCardModal').classList.remove('hidden');
}

function closeRemoveCardModal() {
    document.getElementById('removeCardModal').classList.add('hidden');
}

function confirmRemoveCard() {
    closeRemoveCardModal();
    
    const stripeCard = document.getElementById('stripeCard');
    const momoCard = document.getElementById('momoCard');
    let cardType = 'stripe';
    
    if (stripeCard && !stripeCard.classList.contains('hidden')) {
        cardType = 'stripe';
    } else if (momoCard && !momoCard.classList.contains('hidden')) {
        cardType = 'momo';
    }
    
    $.ajax({
        url: '/profile/removeCard',
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            <?= csrf_token() ?>: '<?= csrf_hash() ?>',
            card_type: cardType
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Có lỗi xảy ra', 'error');
        }
    });
}

// Close remove card modal on outside click
document.getElementById('removeCardModal').addEventListener('click', (e) => {
    if (e.target.id === 'removeCardModal') {
        closeRemoveCardModal();
    }
});

// Payment method selector
document.getElementById('showStripe')?.addEventListener('click', function() {
    document.getElementById('stripeCard').classList.remove('hidden');
    document.getElementById('momoCard').classList.add('hidden');
    this.classList.add('bg-indigo-600', 'text-white', 'active');
    this.classList.remove('bg-gray-300', 'text-gray-700');
    document.getElementById('showMoMo').classList.remove('bg-indigo-600', 'text-white', 'active');
    document.getElementById('showMoMo').classList.add('bg-gray-300', 'text-gray-700');
});

document.getElementById('showMoMo')?.addEventListener('click', function() {
    document.getElementById('stripeCard').classList.add('hidden');
    document.getElementById('momoCard').classList.remove('hidden');
    this.classList.add('bg-indigo-600', 'text-white', 'active');
    this.classList.remove('bg-gray-300', 'text-gray-700');
    document.getElementById('showStripe').classList.remove('bg-indigo-600', 'text-white', 'active');
    document.getElementById('showStripe').classList.add('bg-gray-300', 'text-gray-700');
});

// Create payment chart
const ctx = document.getElementById('paymentChart').getContext('2d');

// Create gold gradient
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(251, 191, 36, 1)'); // Bright gold
gradient.addColorStop(0.5, 'rgba(245, 158, 11, 0.9)'); // Amber
gradient.addColorStop(1, 'rgba(217, 119, 6, 0.8)'); // Darker gold

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Đã thanh toán'],
        datasets: [{
            data: [<?= $total_paid ?>],
            backgroundColor: [gradient],
            borderColor: ['rgba(251, 191, 36, 1)'],
            borderWidth: 4,
            hoverBorderWidth: 8,
            hoverBorderColor: 'rgba(251, 191, 36, 1)',
            shadowColor: 'rgba(251, 191, 36, 0.5)',
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowOffsetY: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 2500,
            easing: 'easeOutElastic'
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                enabled: true,
                backgroundColor: 'rgba(0, 0, 0, 0.9)',
                titleColor: 'rgba(251, 191, 36, 1)',
                bodyColor: 'rgba(251, 191, 36, 1)',
                borderColor: 'rgba(251, 191, 36, 1)',
                borderWidth: 1,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return '💰 Tổng: ' + new Intl.NumberFormat('vi-VN').format(context.parsed) + 'đ';
                    }
                }
            }
        },
        cutout: '80%',
    }
});
</script>

<?= $this->endSection() ?>
