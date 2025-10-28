<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Shop Quần Áo' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <a href="/" class="text-2xl font-bold text-indigo-600">
                    <i class="fas fa-store"></i> Shop Quần Áo
                </a>
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <form action="/products" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." 
                                   value="<?= esc($q ?? '') ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-indigo-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Menu -->
                <div class="flex items-center space-x-6">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 hover:text-indigo-600 focus:outline-none">
                                <i class="fas fa-user"></i>
                                <span><?= esc(session()->get('full_name')) ?></span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <div id="userMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden transition-all duration-200">
                                <a href="/profile" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i>Tài khoản
                                </a>
                                <a href="/profile/orders" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-shopping-bag mr-2"></i>Đơn hàng
                                </a>
                                <?php if (session()->get('role') === 'admin'): ?>
                                    <a href="/admin/dashboard" class="block px-4 py-2 hover:bg-gray-100">
                                        <i class="fas fa-dashboard mr-2"></i>Admin
                                    </a>
                                <?php endif; ?>
                                <a href="/logout" class="block px-4 py-2 hover:bg-gray-100 text-red-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="hover:text-indigo-600"><i class="fas fa-sign-in-alt mr-1"></i> Đăng nhập</a>
                        <a href="/register" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Đăng ký</a>
                    <?php endif; ?>
                    
                    <a href="/cart" class="relative hover:text-indigo-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" id="cart-count">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Shop Quần Áo</h3>
                    <p class="text-gray-400">Thời trang chất lượng cao với giá cả hợp lý</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Liên kết</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/products" class="hover:text-white">Sản phẩm</a></li>
                        <li><a href="/orders/track" class="hover:text-white">Tra cứu đơn hàng</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Chính sách đổi trả</a></li>
                        <li><a href="#" class="hover:text-white">Hướng dẫn thanh toán</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Liên hệ</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i>0123 456 789</li>
                        <li><i class="fas fa-envelope mr-2"></i>shop@email.com</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Shop Quần Áo. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-20 right-4 hidden z-50 animate-slide-in">
        <div class="bg-white rounded-lg shadow-2xl px-6 py-4 flex items-center space-x-4 border-l-4 border-green-500 min-w-[320px] max-w-md">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-900" id="toast-message"></p>
            </div>
            <button onclick="closeToast()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        .animate-slide-out {
            animation: slideOut 0.3s ease-in;
        }
    </style>

    <script>
        let toastTimeout;
        
        function showToast(message, type = 'success') {
            clearTimeout(toastTimeout);
            
            const icons = {
                'success': 'fa-check-circle text-green-500',
                'error': 'fa-exclamation-circle text-red-500',
                'warning': 'fa-exclamation-triangle text-yellow-500',
                'info': 'fa-info-circle text-blue-500'
            };
            
            const borders = {
                'success': 'border-green-500',
                'error': 'border-red-500',
                'warning': 'border-yellow-500',
                'info': 'border-blue-500'
            };
            
            $('#toast i').attr('class', 'fas ' + icons[type] + ' text-2xl');
            $('#toast > div').attr('class', 'bg-white rounded-lg shadow-2xl px-6 py-4 flex items-center space-x-4 border-l-4 ' + borders[type] + ' min-w-[320px] max-w-md');
            $('#toast-message').text(message);
            $('#toast').removeClass('hidden animate-slide-out').addClass('animate-slide-in');
            
            toastTimeout = setTimeout(() => {
                closeToast();
            }, 4000);
        }
        
        function closeToast() {
            $('#toast').removeClass('animate-slide-in').addClass('animate-slide-out');
            setTimeout(() => {
                $('#toast').addClass('hidden');
            }, 300);
        }

        function updateCartCount() {
            $.ajax({
                url: '/cart/count',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#cart-count').text(response.count || 0);
                    }
                }
            });
        }

        // Update cart count on page load
        $(document).ready(function() {
            updateCartCount();
        });

        // Toggle dropdown
        $(document).ready(function() {
            $('.user-menu-toggle').click(function(e) {
                e.stopPropagation();
                $('.user-menu').toggleClass('hidden');
            });
            
            $(document).click(function() {
                $('.user-menu').addClass('hidden');
            });
        });

            const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');

    userMenuButton.addEventListener('click', (e) => {
        e.stopPropagation();
        userMenu.classList.toggle('hidden');
    });

    // Ẩn menu khi click ra ngoài
    document.addEventListener('click', (e) => {
        if (!userMenu.contains(e.target) && !userMenuButton.contains(e.target)) {
            userMenu.classList.add('hidden');
        }
    });
    </script>
</body>
</html>
