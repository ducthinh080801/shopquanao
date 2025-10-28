<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\PaymentModel;
use App\Models\InvoiceModel;
use App\Models\UserModel;
use App\Models\SavedCardModel;

class Checkout extends BaseController
{
    protected $productModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $paymentModel;
    protected $invoiceModel;
    protected $userModel;
    protected $savedCardModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->paymentModel = new PaymentModel();
        $this->invoiceModel = new InvoiceModel();
        $this->userModel = new UserModel();
        $this->savedCardModel = new SavedCardModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Vui lòng đăng nhập để thanh toán');
        }

        $cart = session()->get('cart') ?? [];
        
        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = $this->productModel->find($id);
            if ($product) {
                $price = $product['discount_price'] ?? $product['price'];
                $subtotal = $price * $item['quantity'];
                
                $cartItems[] = [
                    'id' => $id,
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'subtotal' => $subtotal,
                ];
                
                $total += $subtotal;
            }
        }

        // Get user information (includes stripe card info)
        $user = $this->userModel->find(session()->get('user_id'));

        $data = [
            'title' => 'Thanh toán',
            'cart_items' => $cartItems,
            'total' => $total,
            'stripe_key' => 'pk_test_Fuk1wQjIPkQy0rD7Ptmh09x8',
            'user' => $user,
        ];

        return view('frontend/checkout/index', $data);
    }

    public function process()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ]);
        }

        $cart = session()->get('cart') ?? [];
        
        if (empty($cart)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Giỏ hàng trống'
            ]);
        }

        // Get form data
        $shippingName = $this->request->getPost('shipping_name');
        $shippingPhone = $this->request->getPost('shipping_phone');
        $shippingAddress = $this->request->getPost('shipping_address');
        $notes = $this->request->getPost('notes');
        $paymentMethod = $this->request->getPost('payment_method');
        $stripeToken = $this->request->getPost('stripe_token');

        // Calculate total
        $total = 0;
        $orderItems = [];

        foreach ($cart as $id => $item) {
            $product = $this->productModel->find($id);
            if ($product) {
                if ($product['stock'] < $item['quantity']) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Sản phẩm {$product['name']} không đủ hàng trong kho"
                    ]);
                }

                $price = $product['discount_price'] ?? $product['price'];
                $subtotal = $price * $item['quantity'];
                
                $orderItems[] = [
                    'product_id' => $id,
                    'product_name' => $product['name'],
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'subtotal' => $subtotal,
                ];
                
                $total += $subtotal;
            }
        }

        // Create order
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        $orderData = [
            'user_id' => session()->get('user_id'),
            'order_number' => $orderNumber,
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_address' => $shippingAddress,
            'shipping_phone' => $shippingPhone,
            'shipping_name' => $shippingName,
            'notes' => $notes,
        ];

        $orderId = $this->orderModel->insert($orderData);

        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Không thể tạo đơn hàng'
            ]);
        }

        // Insert order items
        foreach ($orderItems as &$item) {
            $item['order_id'] = $orderId;
        }
        $this->orderItemModel->insertBatch($orderItems);

        // Update product stock and sold count
        foreach ($cart as $id => $item) {
            $product = $this->productModel->find($id);
            $this->productModel->update($id, [
                'stock' => $product['stock'] - $item['quantity'],
                'sold_count' => $product['sold_count'] + $item['quantity']
            ]);
        }

        // Process payment based on method
        if ($paymentMethod === 'cod') {
            // COD payment - no payment processing needed
            $paymentData = [
                'order_id' => $orderId,
                'user_id' => session()->get('user_id'),
                'payment_method' => 'cod',
                'transaction_id' => 'COD-' . $orderNumber,
                'amount' => $total,
                'currency' => 'VND',
                'status' => 'pending',
                'payment_details' => json_encode(['method' => 'Cash on Delivery']),
            ];

            $paymentId = $this->paymentModel->insert($paymentData);

            // Update order status to pending (waiting for delivery)
            $this->orderModel->update($orderId, ['status' => 'pending']);

            // Generate invoice
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
            $invoiceData = [
                'order_id' => $orderId,
                'payment_id' => $paymentId,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => date('Y-m-d H:i:s'),
            ];

            $this->invoiceModel->insert($invoiceData);

            // Clear cart
            session()->remove('cart');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đặt hàng thành công. Thanh toán khi nhận hàng.',
                'order_number' => $orderNumber,
                'redirect' => '/orders/success/' . $orderNumber
            ]);

        } elseif ($paymentMethod === 'momo') {
            try {
                $transactionId = 'MOMO-' . strtoupper(substr(md5(uniqid()), 0, 10));

                $paymentData = [
                    'order_id' => $orderId,
                    'user_id' => session()->get('user_id'),
                    'payment_method' => 'momo',
                    'transaction_id' => $transactionId,
                    'amount' => $total,
                    'currency' => 'VND',
                    'status' => 'completed',
                    'payment_details' => json_encode([
                        'method' => 'MoMo Wallet',
                        'phone' => session()->get('momo_phone') ?? 'N/A'
                    ]),
                ];

                $paymentId = $this->paymentModel->insert($paymentData);
                $this->orderModel->update($orderId, ['status' => 'processing']);
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
                $invoiceData = [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId,
                    'invoice_number' => $invoiceNumber,
                    'invoice_date' => date('Y-m-d H:i:s'),
                ];

                $this->invoiceModel->insert($invoiceData);
                session()->remove('cart');

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Thanh toán MoMo thành công',
                    'order_number' => $orderNumber,
                    'redirect' => '/orders/success/' . $orderNumber
                ]);

            } catch (\Exception $e) {
                // Delete order if payment fails
                $this->orderModel->delete($orderId);

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Thanh toán MoMo thất bại: ' . $e->getMessage()
                ]);
            }

        } else {
            // Stripe payment
            try {
                // Check if using saved card (mock token)
                $usingSavedCard = ($stripeToken === 'tok_visa');
                
                if ($usingSavedCard) {
                    // Using saved card - skip Stripe API call (already verified)
                    $transactionId = 'SAVED-' . strtoupper(substr(md5(uniqid()), 0, 10));
                    
                    $paymentData = [
                        'order_id' => $orderId,
                        'user_id' => session()->get('user_id'),
                        'payment_method' => 'stripe',
                        'transaction_id' => $transactionId,
                        'amount' => $total,
                        'currency' => 'USD',
                        'status' => 'completed',
                        'stripe_payment_intent' => $transactionId,
                        'payment_details' => json_encode(['method' => 'Saved Card', 'card_last4' => '4242']),
                    ];
                } else {
                    // Using new card - process with Stripe API
                    $vendorPath = ROOTPATH . 'vendor/autoload.php';
                    if (!file_exists($vendorPath)) {
                        throw new \Exception('Stripe library not found. Please run: composer require stripe/stripe-php');
                    }
                    require_once $vendorPath;
                    
                    if (empty($stripeToken)) {
                        throw new \Exception('Stripe token is required');
                    }
                    
                    \Stripe\Stripe::setApiKey('sk_test_AkIyLORA5oDaKdLxUc3gUdwN');

                    $charge = \Stripe\Charge::create([
                        'amount' => $total * 100, // Convert to cents
                        'currency' => 'usd',
                        'source' => $stripeToken,
                        'description' => 'Order ' . $orderNumber,
                    ]);

                    $paymentData = [
                        'order_id' => $orderId,
                        'user_id' => session()->get('user_id'),
                        'payment_method' => 'stripe',
                        'transaction_id' => $charge->id,
                        'amount' => $total,
                        'currency' => 'USD',
                        'status' => 'completed',
                        'stripe_payment_intent' => $charge->id,
                        'payment_details' => json_encode($charge),
                    ];
                }

                $paymentId = $this->paymentModel->insert($paymentData);

                // Update order status
                $this->orderModel->update($orderId, ['status' => 'processing']);

                // Generate invoice
                $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
                $invoiceData = [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId,
                    'invoice_number' => $invoiceNumber,
                    'invoice_date' => date('Y-m-d H:i:s'),
                ];

                $this->invoiceModel->insert($invoiceData);

                // Clear cart
                session()->remove('cart');

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Thanh toán thành công',
                    'order_number' => $orderNumber,
                    'redirect' => '/orders/success/' . $orderNumber
                ]);

            } catch (\Exception $e) {
                // Delete order if payment fails
                $this->orderModel->delete($orderId);
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Thanh toán thất bại: ' . $e->getMessage()
                ]);
            }
        }
    }
}
