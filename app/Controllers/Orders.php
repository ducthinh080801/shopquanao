<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\InvoiceModel;
use App\Models\PaymentModel;

class Orders extends BaseController
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
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $orders = $this->orderModel->getUserOrders(session()->get('user_id'));

        $data = [
            'title' => 'Đơn hàng của tôi',
            'orders' => $orders,
        ];

        return view('frontend/orders/index', $data);
    }

    public function detail($orderNumber)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $order = $this->orderModel->getOrderByNumber($orderNumber);

        if (!$order || $order['user_id'] != session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get order items
        $orderItems = $this->orderItemModel->getOrderItems($order['id']);
        
        // Get payment info
        $payment = $this->paymentModel->where('order_id', $order['id'])->first();

        $data = [
            'title' => 'Chi tiết đơn hàng #' . $orderNumber,
            'order' => $order,
            'order_items' => $orderItems,
            'payment' => $payment,
        ];

        return view('frontend/orders/detail', $data);
    }

    public function track()
    {
        $data = ['title' => 'Tra cứu đơn hàng'];
        return view('frontend/orders/track', $data);
    }

    public function trackPost()
    {
        $orderNumber = $this->request->getPost('order_number');
        $order = $this->orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            return redirect()->to('/orders/track')->with('error', 'Không tìm thấy đơn hàng với mã: ' . $orderNumber);
        }

        $data = [
            'title' => 'Tra cứu đơn hàng',
            'order' => $order
        ];

        return view('frontend/orders/track', $data);
    }

    public function success($orderNumber)
    {
        $order = $this->orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Đặt hàng thành công',
            'order' => $order,
            'order_number' => $orderNumber,
        ];

        return view('frontend/orders/success', $data);
    }

    public function invoice($invoiceNumber)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $invoice = $this->invoiceModel->getInvoiceByNumber($invoiceNumber);

        if (!$invoice || ($invoice['user_id'] != session()->get('user_id') && session()->get('role') !== 'admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get order items
        $orderItems = $this->orderItemModel->getOrderItems($invoice['order_id']);
        
        // Get order
        $order = $this->orderModel->find($invoice['order_id']);

        $data = [
            'title' => 'Hóa đơn #' . $invoiceNumber,
            'invoice' => $invoice,
            'order_items' => $orderItems,
            'order' => $order,
        ];

        return view('frontend/orders/invoice', $data);
    }

    public function downloadInvoice($invoiceNumber)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $invoice = $this->invoiceModel->getInvoiceByNumber($invoiceNumber);

        if (!$invoice || ($invoice['user_id'] != session()->get('user_id') && session()->get('role') !== 'admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get order items
        $orderItems = $this->orderItemModel->getOrderItems($invoice['order_id']);
        $invoice['items'] = $orderItems;

        // Generate PDF
        $dompdf = new \Dompdf\Dompdf();
        $html = view('frontend/orders/invoice_pdf', ['invoice' => $invoice]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($dompdf->output());
    }
}
