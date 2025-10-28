<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\InvoiceModel;
use App\Models\PaymentModel;

class AdminOrders extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $invoiceModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->invoiceModel = new InvoiceModel();
        $this->paymentModel = new PaymentModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $status = $this->request->getGet('status');
        
        if ($status) {
            $orders = $this->orderModel->getOrdersByStatus($status);
        } else {
            $orders = $this->orderModel->select('orders.*, users.full_name, users.email')
                ->join('users', 'users.id = orders.user_id')
                ->orderBy('orders.created_at', 'DESC')
                ->paginate(20);
        }

        $data = [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'pager' => $this->orderModel->pager,
            'status_filter' => $status,
        ];

        return view('admin/orders/index', $data);
    }

    public function detail($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $order = $this->orderModel->find($id);
        
        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get order items
        $orderItems = $this->orderItemModel->getOrderItems($id);
        
        // Get payment info
        $payment = $this->paymentModel->where('order_id', $id)->first();

        $data = [
            'title' => 'Chi tiết đơn hàng #' . $order['order_number'],
            'order' => $order,
            'order_items' => $orderItems,
            'payment' => $payment,
        ];

        return view('admin/orders/detail', $data);
    }

    public function updateStatus()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $orderId = $this->request->getPost('order_id');
        $status = $this->request->getPost('status');

        if ($this->orderModel->update($orderId, ['status' => $status])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }

    public function invoices()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $invoices = $this->invoiceModel->select('invoices.*, orders.order_number, orders.total_amount, users.full_name as customer_name, users.email as customer_email')
            ->join('orders', 'orders.id = invoices.order_id')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('invoices.created_at', 'DESC')
            ->paginate(20);

        $data = [
            'title' => 'Quản lý hóa đơn',
            'invoices' => $invoices,
            'pager' => $this->invoiceModel->pager,
        ];

        return view('admin/orders/invoices', $data);
    }
}
