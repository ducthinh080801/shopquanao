<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Dashboard') ?> - Shop Quần Áo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        html {
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white">
        <div class="p-6">
            <h2 class="text-2xl font-bold">
                Admin Manager
            </h2>
        </div>
        
        <nav class="mt-6">
            <a href="/admin/dashboard" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (uri_string() === 'admin/dashboard') ? 'bg-gray-800 border-l-4 border-indigo-500' : '' ?>">
                <i class="fas fa-chart-line w-6"></i>
                <span>Dashboard</span>
            </a>
            <a href="/admin/products" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (strpos(uri_string(), 'admin/products') !== false) ? 'bg-gray-800 border-l-4 border-indigo-500' : '' ?>">
                <i class="fas fa-box w-6"></i>
                <span>Sản phẩm</span>
            </a>
            <a href="/admin/orders" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (strpos(uri_string(), 'admin/orders') !== false && strpos(uri_string(), 'admin/orders/invoices') === false) ? 'bg-gray-800 border-l-4 border-indigo-500' : '' ?>">
                <i class="fas fa-shopping-cart w-6"></i>
                <span>Đơn hàng</span>
            </a>
            <a href="/admin/customers" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (strpos(uri_string(), 'admin/customers') !== false) ? 'bg-gray-800 border-l-4 border-indigo-500' : '' ?>">
                <i class="fas fa-users w-6"></i>
                <span>Khách hàng</span>
            </a>
            <a href="/admin/reviews" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (strpos(uri_string(), 'admin/reviews') !== false) ? 'bg-gray-800 border-l-4 border-indigo-500' : '' ?>">
                <i class="fas fa-star w-6"></i>
                <span>Đánh giá</span>
            </a>

            <a href="/admin/orders/invoices" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (strpos(uri_string(), 'admin/orders/invoices') !== false) ? 'bg-gray-800 border-l-4 border-indigo-500' : '' ?>">
                <i class="fas fa-file-invoice w-6"></i>
                <span>Hóa đơn</span>
            </a>
            <div class="border-t border-gray-700 my-4"></div>

            <a href="/logout" class="flex items-center px-6 py-3 hover:bg-gray-800 text-red-400">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>Đăng xuất</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64">
        <!-- Top Bar -->
        <div class="bg-white shadow-md px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h1 class="text-2xl font-bold text-gray-800"><?= esc($title ?? 'Dashboard') ?></h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">
                    <i class="fas fa-user-circle mr-2"></i><?= esc(session()->get('full_name')) ?>
                </span>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-6">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 hidden z-50 animate-slide-in">
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
    </script>
</body>
</html>
