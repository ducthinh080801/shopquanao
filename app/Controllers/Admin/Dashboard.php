<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\ReviewModel;

class Dashboard extends BaseController
{
    protected $orderModel;
    protected $productModel;
    protected $userModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        // Get statistics
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $monthlyRevenue = $this->orderModel->getTotalRevenue(
            date('Y-m-01 00:00:00'),
            date('Y-m-t 23:59:59')
        );
        
        $totalOrders = $this->orderModel->countAllResults();
        $totalProducts = $this->productModel->countAllResults();
        $totalUsers = $this->userModel->where('role', 'user')->countAllResults();
        
        $pendingOrders = $this->orderModel->where('status', 'pending')->countAllResults();
        $lowStockProducts = $this->productModel->getLowStock(5);
        $recentOrders = $this->orderModel->getRecentOrders(10);
        
        $bestSellers = $this->productModel->getBestSellers(5);
        $lowStock = $lowStockProducts;
        
        // Get revenue data for chart (last 12 months)
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $revenueLabels[] = date('M Y', strtotime("-$i months"));
            
            $monthStart = date('Y-m-01 00:00:00', strtotime("-$i months"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("-$i months"));
            
            $revenue = $this->orderModel
                ->where('status', 'completed')
                ->where('created_at >=', $monthStart)
                ->where('created_at <=', $monthEnd)
                ->selectSum('total_amount')
                ->first();
            
            $revenueData[] = $revenue['total_amount'] ?? 0;
        }
        
        // Get order status data for chart
        $orderStatusData = [
            $this->orderModel->where('status', 'pending')->countAllResults(),
            $this->orderModel->where('status', 'processing')->countAllResults(),
            $this->orderModel->where('status', 'shipping')->countAllResults(),
            $this->orderModel->where('status', 'completed')->countAllResults(),
            $this->orderModel->where('status', 'cancelled')->countAllResults(),
        ];

        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'total_revenue' => $totalRevenue ?: 0,
                'total_orders' => $totalOrders,
                'total_products' => $totalProducts,
                'total_customers' => $totalUsers,
            ],
            'best_sellers' => $bestSellers,
            'low_stock' => $lowStock,
            'revenue_labels' => $revenueLabels,
            'revenue_data' => $revenueData,
            'order_status_data' => $orderStatusData,
        ];

        return view('admin/dashboard/index', $data);
    }

    public function revenueChart()
    {
        // Get revenue for last 12 months
        $revenues = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($startDate));
            
            $revenue = $this->orderModel->getTotalRevenue($startDate, $endDate);
            $revenues[] = $revenue;
            $labels[] = date('M Y', strtotime($startDate));
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'data' => $revenues
        ]);
    }
}
