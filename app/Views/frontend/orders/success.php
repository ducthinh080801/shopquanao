<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-white rounded-xl shadow-lg p-12">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-green-600 text-5xl"></i>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-4">Đặt hàng thành công!</h1>
            <p class="text-gray-600 mb-2">Cảm ơn bạn đã đặt hàng</p>
            <p class="text-xl font-bold text-indigo-600 mb-8">Mã đơn hàng: #<?= esc($order_number) ?></p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <p class="text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Đơn hàng của bạn đang được xử lý. Chúng tôi sẽ gửi thông báo khi đơn hàng được giao.
                </p>
            </div>

            <div class="flex gap-4 justify-center">
                <a href="/profile/orders"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700">
                    <i class="fas fa-list mr-2"></i>Xem đơn hàng của tôi
                </a>
                <a href="/products"
                    class="border border-indigo-600 text-indigo-600 px-8 py-3 rounded-lg font-bold hover:bg-indigo-50">
                    <i class="fas fa-shopping-bag mr-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<?= $this->endSection() ?>