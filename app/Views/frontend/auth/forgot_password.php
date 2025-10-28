<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Shop Quần Áo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-key text-indigo-600 text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Quên mật khẩu?</h1>
                    <p class="text-gray-600">Nhập email để nhận hướng dẫn đặt lại mật khẩu</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="/forgot-password" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="email@example.com">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition duration-200">
                        <i class="fas fa-paper-plane mr-2"></i>Gửi yêu cầu
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="/login" class="text-indigo-600 font-bold hover:text-indigo-700">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại đăng nhập
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
