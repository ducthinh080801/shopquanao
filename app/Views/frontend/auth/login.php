<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Shop Quần Áo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-store text-indigo-600"></i> Shop Quần Áo
                    </h1>
                    <p class="text-gray-600">Đăng nhập để tiếp tục</p>
                </div>

                <form id="loginForm">
                    <div class="mb-4">
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

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Mật khẩu</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-sm text-gray-600">Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="/forgot-password" class="text-sm text-indigo-600 hover:underline">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" 
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Chưa có tài khoản? 
                        <a href="/register" class="text-indigo-600 font-bold hover:underline">Đăng ký ngay</a>
                    </p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm font-bold text-gray-700 mb-2">Tài khoản demo:</p>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Admin:</strong> admin@example.com / admin123</p>
                            <p><strong>User:</strong> user1@example.com / user123</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="/" class="text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 hidden">
        <div class="bg-white rounded-lg shadow-lg px-6 py-4 flex items-center space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900" id="toast-message"></p>
            </div>
        </div>
    </div>

    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                email: $('input[name="email"]').val(),
                password: $('input[name="password"]').val()
            };

            $.post('/login', formData, function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    showToast(response.message, 'error');
                }
            });
        });

        function showToast(message, type = 'success') {
            const icon = type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500';
            $('#toast i').attr('class', 'fas ' + icon + ' text-xl');
            $('#toast-message').text(message);
            $('#toast').removeClass('hidden');
            setTimeout(() => {
                $('#toast').addClass('hidden');
            }, 3000);
        }
    </script>
</body>
</html>
