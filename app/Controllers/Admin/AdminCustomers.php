<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminCustomers extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $customers = $this->userModel->where('role', 'user')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        $data = [
            'title' => 'Quản lý khách hàng',
            'customers' => $customers,
            'pager' => $this->userModel->pager,
        ];

        return view('admin/customers/index', $data);
    }

    public function detail($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $customer = $this->userModel->find($id);
        
        if (!$customer || $customer['role'] !== 'user') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get customer orders
        $orderModel = new \App\Models\OrderModel();
        $orders = $orderModel->where('user_id', $id)->findAll();
        
        // Calculate stats
        $totalOrders = count($orders);
        $totalSpent = array_sum(array_column($orders, 'total_amount'));
        
        // Get reviews count
        $reviewModel = new \App\Models\ReviewModel();
        $totalReviews = $reviewModel->where('user_id', $id)->countAllResults();

        $data = [
            'title' => 'Chi tiết khách hàng',
            'customer' => $customer,
            'orders' => $orders,
            'stats' => [
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'total_reviews' => $totalReviews,
            ],
        ];

        return view('admin/customers/detail', $data);
    }

    public function toggleStatus($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $customer = $this->userModel->find($id);
        
        if (!$customer) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Customer not found'
            ]);
        }

        $newStatus = $customer['is_active'] ? 0 : 1;
        
        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $newStatus ? 'Đã kích hoạt tài khoản' : 'Đã khóa tài khoản'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }
}
