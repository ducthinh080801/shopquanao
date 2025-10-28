<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Thanh toán</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form id="checkoutForm">
                <!-- Shipping Information -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">Thông tin giao hàng</h3>
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Họ và tên người nhận *</label>
                        <input type="text" name="shipping_name" required
                               value="<?= esc($user['full_name'] ?? session()->get('full_name')) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Số điện thoại *</label>
                        <input type="tel" name="shipping_phone" required
                               value="<?= esc($user['phone'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                               placeholder="Nhập số điện thoại">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Địa chỉ giao hàng *</label>
                        <textarea name="shipping_address" rows="3" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Nhập địa chỉ giao hàng"><?= esc($user['address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Ghi chú</label>
                        <textarea name="notes" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                  placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">Phương thức thanh toán</h3>
                    
                    <!-- Payment Method Selection -->
                    <div class="space-y-3 mb-6">
                        <!-- COD Payment -->
                        <div class="p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-600 transition payment-method-item"
                             data-method="cod"
                             onclick="selectPaymentMethod('cod')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill-wave text-3xl text-green-600 mr-3"></i>
                                    <div>
                                        <p class="font-bold">Thanh toán khi nhận hàng (COD)</p>
                                        <p class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                    </div>
                                </div>
                                <div class="payment-selected-indicator hidden">
                                    <i class="fas fa-check-circle text-indigo-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stripe Payment -->
                        <div class="p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-600 transition payment-method-item"
                             data-method="stripe"
                             onclick="selectPaymentMethod('stripe')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fab fa-cc-stripe text-3xl text-indigo-600 mr-3"></i>
                                    <div>
                                        <p class="font-bold">Thanh toán bằng thẻ</p>
                                        <p class="text-sm text-gray-600">Thanh toán bằng thẻ tín dụng/ghi nợ</p>
                                    </div>
                                </div>
                                <div class="payment-selected-indicator hidden">
                                    <i class="fas fa-check-circle text-indigo-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- MoMo Payment -->
                        <div class="p-4 border-2 rounded-lg cursor-pointer hover:border-pink-600 transition payment-method-item"
                             data-method="momo"
                             onclick="selectPaymentMethod('momo')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-mobile-alt text-3xl text-pink-600 mr-3"></i>
                                    <div>
                                        <p class="font-bold">Ví MoMo</p>
                                        <p class="text-sm text-gray-600">Thanh toán nhanh qua ứng dụng MoMo</p>
                                    </div>
                                </div>
                                <div class="payment-selected-indicator hidden">
                                    <i class="fas fa-check-circle text-pink-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="payment_method" id="paymentMethod" value="cod">
                    
                    <!-- Stripe Payment Details (Hidden by default) -->
                    <div id="stripePaymentSection" class="hidden">
                        <!-- Saved Cards -->
                        <div id="savedCardsSection" class="mb-4"></div>
                        
                        <div id="noPaymentMethodAlert" class="hidden">
                            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-300 rounded-xl p-6 mb-4">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-credit-card text-amber-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">
                                            <i class="fas fa-exclamation-circle text-amber-600 mr-2"></i>
                                            Bạn chưa thêm phương thức thanh toán
                                        </h4>
                                        <p class="text-gray-700 mb-4">
                                            Để thanh toán bằng Stripe, bạn cần thêm thẻ tín dụng/ghi nợ vào tài khoản của mình trước.
                                        </p>
                                        <a href="http://localhost:8080/profile/payments" 
                                           class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            <i class="fas fa-plus-circle"></i>
                                            <span>Thêm phương thức thanh toán</span>
                                            <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add New Card -->
                        <div id="newCardSection" class="mb-4 p-4 border-2 border-gray-300 rounded-lg hidden">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-cc-stripe text-3xl text-indigo-600 mr-3"></i>
                                    <span class="font-bold">Thanh toán bằng thẻ</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="block text-sm font-bold mb-1">Số thẻ</label>
                                    <div id="cardNumber" class="p-3 border border-gray-300 rounded-lg"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-bold mb-1">Ngày hết hạn</label>
                                        <div id="cardExpiry" class="p-3 border border-gray-300 rounded-lg"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold mb-1">CVC</label>
                                        <div id="cardCvc" class="p-3 border border-gray-300 rounded-lg"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="card-errors" class="text-red-600 text-sm mt-2"></div>
                            
                            <p class="text-sm text-gray-600 mt-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                Test card: 4242 4242 4242 4242 | MM/YY: 12/34 | CVC: 123
                            </p>
                        </div>
                        
                        <input type="hidden" name="selected_card_id" id="selectedCardId">
                    </div>

                    <!-- MoMo Payment Details (Hidden by default) -->
                    <div id="momoPaymentSection" class="hidden">
                        <div id="momoLinkedSection" class="mb-4"></div>

                        <div id="noMomoAlert" class="hidden">
                            <div class="bg-gradient-to-r from-pink-50 to-rose-50 border-2 border-pink-300 rounded-xl p-6 mb-4">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-mobile-alt text-pink-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-800 mb-2">
                                            <i class="fas fa-exclamation-circle text-pink-600 mr-2"></i>
                                            Bạn chưa liên kết tài khoản MoMo
                                        </h4>
                                        <p class="text-gray-700 mb-4">
                                            Để thanh toán bằng MoMo, bạn cần liên kết tài khoản MoMo vào hồ sơ của mình trước.
                                        </p>
                                        <a href="http://localhost:8080/profile/payments"
                                           class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            <i class="fas fa-mobile-alt"></i>
                                            <span>Liên kết MoMo</span>
                                            <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submitBtn"
                        class="w-full mt-6 bg-indigo-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition-all">
                    <i class="fas fa-lock mr-2"></i>Xác nhận thanh toán
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-xl font-bold mb-4">Đơn hàng</h3>
                
                <div class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="flex gap-3 pb-3 border-b">
                            <img src="<?= esc($item['image']) ?>" alt="" class="w-16 h-16 object-cover rounded">
                            <div class="flex-1">
                                <p class="font-bold text-sm"><?= esc($item['name']) ?></p>
                                <p class="text-gray-600 text-sm">SL: <?= $item['quantity'] ?></p>
                                <p class="text-indigo-600 font-bold"><?= number_format($item['subtotal']) ?>đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="space-y-3 mb-4 pt-4 border-t">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tạm tính:</span>
                        <span class="font-bold"><?= number_format($total) ?>đ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phí vận chuyển:</span>
                        <span class="font-bold text-green-600">Miễn phí</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-xl">
                        <span class="font-bold">Tổng cộng:</span>
                        <span class="font-bold text-indigo-600"><?= number_format($total) ?>đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
// Stripe setup
const stripe = Stripe('<?= $stripe_key ?>');
const elements = stripe.elements();

// Create separate card elements
const cardNumber = elements.create('cardNumber');
const cardExpiry = elements.create('cardExpiry');
const cardCvc = elements.create('cardCvc');

cardNumber.mount('#cardNumber');
cardExpiry.mount('#cardExpiry');
cardCvc.mount('#cardCvc');

// Error handling
cardNumber.on('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Check if user has saved card
const hasCard = <?= !empty($user['stripe_card_id']) ? 'true' : 'false' ?>;
const userCard = hasCard ? {
    id: '<?= $user['stripe_card_id'] ?? '' ?>',
    last4: '<?= $user['stripe_last4'] ?? '' ?>',
    brand: '<?= $user['stripe_brand'] ?? 'visa' ?>',
    exp_month: '<?= $user['stripe_exp_month'] ?? '' ?>',
    exp_year: '<?= $user['stripe_exp_year'] ?? '' ?>',
    name: '<?= $user['stripe_card_name'] ?? '' ?>'
} : null;

console.log('User has card:', hasCard, userCard);

// Check if user has MoMo linked
const hasMomo = <?= !empty($user['momo_phone']) ? 'true' : 'false' ?>;
const userMomo = hasMomo ? {
    phone: '<?= $user['momo_phone'] ?? '' ?>',
    name: '<?= $user['momo_name'] ?? '' ?>'
} : null;

console.log('User has MoMo:', hasMomo, userMomo);

// Display linked MoMo
function displayLinkedMomo() {
    const momoLinkedSection = document.getElementById('momoLinkedSection');
    momoLinkedSection.innerHTML = ''; // Clear first
    
    if (hasMomo && userMomo) {
        let momoHTML = '<div class="space-y-3">';
        momoHTML += '<h4 class="font-bold text-sm text-gray-700 mb-2">Tài khoản MoMo:</h4>';
        momoHTML += `
            <div class="p-4 border-2 border-pink-600 rounded-lg bg-pink-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-mobile-alt text-3xl text-pink-600 mr-3"></i>
                        <div>
                            <p class="font-bold">*** *** **** ${userMomo.phone.slice(-3)}</p>
                            <p class="text-sm text-gray-600">${userMomo.name}</p>
                            <p class="text-xs text-gray-500">Đã liên kết</p>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-check-circle text-pink-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        `;
        
        momoHTML += '</div>';
        
        momoLinkedSection.innerHTML = momoHTML;
    }
}

// Display saved card
function displaySavedCard() {
    const savedCardsSection = document.getElementById('savedCardsSection');
    savedCardsSection.innerHTML = ''; // Clear first
    
    if (hasCard && userCard) {
        const expiry = userCard.exp_month + '/' + userCard.exp_year;
        const brandIcon = userCard.brand.toLowerCase() === 'visa' ? 'fab fa-cc-visa' : 'fab fa-cc-mastercard';
        
        let cardsHTML = '<div class="space-y-3">';
        cardsHTML += '<h4 class="font-bold text-sm text-gray-700 mb-2">Thẻ đã lưu:</h4>';
        cardsHTML += `
            <div class="p-4 border-2 border-indigo-600 rounded-lg bg-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="${brandIcon} text-3xl text-indigo-600 mr-3"></i>
                        <div>
                            <p class="font-bold">•••• •••• •••• ${userCard.last4}</p>
                            <p class="text-sm text-gray-600">${userCard.name}</p>
                            <p class="text-xs text-gray-500">Hết hạn: ${expiry}</p>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-check-circle text-indigo-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        `;
        
        cardsHTML += '</div>';
        
        // Add "Use new card" button
        cardsHTML += `
            <button type="button" onclick="showNewCardSection()" 
                    class="w-full mt-3 p-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-600 transition text-gray-600 font-bold">
                <i class="fas fa-plus mr-2"></i>Sử dụng thẻ mới
            </button>
        `;
        
        savedCardsSection.innerHTML = cardsHTML;
        
        // Set selected card ID
        document.getElementById('selectedCardId').value = userCard.id;
    }
}

// Select saved card (not needed for single card, but keep for compatibility)
function selectSavedCard(cardId) {
    document.getElementById('selectedCardId').value = cardId;
    document.getElementById('newCardSection').classList.add('hidden');
}

// Show new card section
function showNewCardSection() {
    document.getElementById('selectedCardId').value = '';
    document.querySelectorAll('.saved-card-item').forEach(item => {
        item.classList.remove('border-indigo-600');
        item.querySelector('.selected-indicator').classList.add('hidden');
    });
    document.getElementById('newCardSection').classList.remove('hidden');
}

// Payment method selection
function selectPaymentMethod(method) {
    // Update hidden input
    document.getElementById('paymentMethod').value = method;
    
    // Remove all selections
    document.querySelectorAll('.payment-method-item').forEach(item => {
        item.classList.remove('border-indigo-600', 'border-pink-600');
        item.querySelector('.payment-selected-indicator').classList.add('hidden');
    });
    
    // Select clicked method
    const selectedMethod = document.querySelector(`[data-method="${method}"]`);
    if (selectedMethod) {
        const borderColor = method === 'momo' ? 'border-pink-600' : 'border-indigo-600';
        selectedMethod.classList.add(borderColor);
        selectedMethod.querySelector('.payment-selected-indicator').classList.remove('hidden');
    }
    
    // Show/hide sections based on payment method
    const stripeSection = document.getElementById('stripePaymentSection');
    const momoSection = document.getElementById('momoPaymentSection');
    const submitBtn = document.getElementById('submitBtn');
    
    if (method === 'stripe') {
        stripeSection.classList.remove('hidden');
        momoSection.classList.add('hidden');
        initializeStripeSection();
    } else if (method === 'momo') {
        stripeSection.classList.add('hidden');
        momoSection.classList.remove('hidden');
        initializeMomoSection();
    } else {
        stripeSection.classList.add('hidden');
        momoSection.classList.add('hidden');
        
        // Re-enable submit button for COD
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i>Xác nhận thanh toán';
    }
}

// Initialize Stripe section based on user card
function initializeStripeSection() {
    const submitBtn = document.getElementById('submitBtn');
    
    if (!hasCard) {
        // No saved card - show alert with link to add payment method
        document.getElementById('savedCardsSection').innerHTML = '';
        document.getElementById('newCardSection').classList.add('hidden');
        document.getElementById('noPaymentMethodAlert').classList.remove('hidden');
        
        // Disable submit button and change appearance
        submitBtn.disabled = true;
        submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i>Vui lòng thêm phương thức thanh toán';
    } else {
        // Has saved card - display it and hide alert/new card form
        displaySavedCard();
        document.getElementById('newCardSection').classList.add('hidden');
        document.getElementById('noPaymentMethodAlert').classList.add('hidden');
        
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i>Xác nhận thanh toán';
    }
}

// Initialize MoMo section based on user MoMo
function initializeMomoSection() {
    const submitBtn = document.getElementById('submitBtn');
    
    if (!hasMomo) {
        // No linked MoMo - show alert with link to link MoMo
        document.getElementById('momoLinkedSection').innerHTML = '';
        document.getElementById('noMomoAlert').classList.remove('hidden');
        
        // Disable submit button and change appearance
        submitBtn.disabled = true;
        submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i>Vui lòng liên kết MoMo';
    } else {
        // Has linked MoMo - display it and hide alert
        displayLinkedMomo();
        document.getElementById('noMomoAlert').classList.add('hidden');
        
        // Enable submit button
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        submitBtn.innerHTML = '<i class="fas fa-lock mr-2"></i>Xác nhận thanh toán';
    }
}

// Auto-select COD on page load
selectPaymentMethod('cod');

$('#checkoutForm').submit(async function(e) {
    e.preventDefault();
    
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...');
    
    const paymentMethod = document.getElementById('paymentMethod').value;
    const formData = $(this).serializeArray();

    let stripeToken = '';

    if (paymentMethod === 'cod') {
        formData.push({ name: 'stripe_token', value: '' });
    } else if (paymentMethod === 'stripe') {
        const selectedCardId = document.getElementById('selectedCardId').value;
        if (selectedCardId && hasCard) {
            stripeToken = 'tok_visa';
        } else {
            try {
                const result = await stripe.createToken(cardNumber);
                
                if (result.error) {
                    $('#card-errors').text(result.error.message);
                    submitBtn.prop('disabled', false).html('<i class="fas fa-lock mr-2"></i>Xác nhận thanh toán');
                    return;
                }
                
                console.log('Stripe token created:', result.token);
                stripeToken = result.token.id;
            } catch (err) {
                console.error('Stripe error:', err);
                $('#card-errors').text('Có lỗi xảy ra khi tạo token');
                submitBtn.prop('disabled', false).html('<i class="fas fa-lock mr-2"></i>Xác nhận thanh toán');
                return;
            }
        }
        
        formData.push({ name: 'stripe_token', value: stripeToken });
    } else if (paymentMethod === 'momo') {
        // MoMo payment - no token needed, just mark as MoMo
        formData.push({ name: 'stripe_token', value: 'momo_payment' });
    }
    
    // ✅ stripeToken luôn có giá trị (ít nhất là chuỗi rỗng)
    console.log('Submitting payment:', { paymentMethod, stripeToken });
    
    $.ajax({
        url: '/checkout/process',
        method: 'POST',
        data: $.param(formData),
        success: function(response) {
            console.log('Payment response:', response);
            if (response.success) {
                showToast(response.message);
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 1000);
            } else {
                showToast(response.message, 'error');
                submitBtn.prop('disabled', false).html('<i class="fas fa-lock mr-2"></i>Xác nhận thanh toán');
            }
        },
        error: function(xhr, status, error) {
            console.error('Payment error:', xhr.responseText);
            showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            submitBtn.prop('disabled', false).html('<i class="fas fa-lock mr-2"></i>Xác nhận thanh toán');
        }
    });
});

</script>

<?= $this->endSection() ?>
