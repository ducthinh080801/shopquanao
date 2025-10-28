<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\InvoiceModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $orderModel;
    protected $paymentModel;
    protected $invoiceModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->paymentModel = new PaymentModel();
        $this->invoiceModel = new InvoiceModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Get recent invoices
        $invoices = $this->invoiceModel->getUserInvoices($userId);
        $invoices = array_slice($invoices, 0, 5); // Limit to 5 recent

        $data = [
            'title' => 'Thông tin cá nhân',
            'user' => $user,
            'invoices' => $invoices,
            'tab' => 'profile',
        ];

        return view('frontend/profile/index', $data);
    }

    public function update()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ]);
        }

        $userId = session()->get('user_id');
        
        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        if ($this->userModel->update($userId, $userData)) {
            // Update session
            session()->set('full_name', $userData['full_name']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }

    public function changePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ]);
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (!password_verify($currentPassword, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng'
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Mật khẩu xác nhận không khớp'
            ]);
        }

        if (strlen($newPassword) < 6) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Mật khẩu phải có ít nhất 6 ký tự'
            ]);
        }

        $userData = [
            'password' => $newPassword
        ];

        if ($this->userModel->update($userId, $userData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }

    public function orders()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $orders = $this->orderModel->getUserOrders($userId);

        $data = [
            'title' => 'Lịch sử mua hàng',
            'orders' => $orders,
            'tab' => 'orders',
        ];

        return view('frontend/profile/orders', $data);
    }

    public function payments()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $payments = $this->paymentModel->getUserPayments($userId);
        $totalPaid = array_sum(array_column($payments, 'amount'));
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Lịch sử thanh toán',
            'payments' => $payments,
            'total_paid' => $totalPaid,
            'user' => $user,
            'tab' => 'payments',
            'stripe_key' => 'pk_test_Fuk1wQjIPkQy0rD7Ptmh09x8',
        ];

        return view('frontend/profile/payments', $data);
    }
    
    public function addCard()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ]);
        }

        $userId = session()->get('user_id');
        $stripeToken = $this->request->getPost('stripe_token');
        $momoPhone = $this->request->getPost('momo_phone');
        $cardName = $this->request->getPost('card_name');
        $paymentMethod = $this->request->getPost('payment_method') ?? 'stripe';

        if ($paymentMethod === 'stripe' && empty($stripeToken)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Thông tin thẻ không hợp lệ'
            ]);
        }

        if ($paymentMethod === 'momo' && empty($momoPhone)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Số điện thoại MoMo không hợp lệ'
            ]);
        }

        try {
            if ($paymentMethod === 'stripe') {
                if (!class_exists('\Stripe\Stripe')) {
                    $vendorPath = ROOTPATH . 'vendor/autoload.php';
                    if (!file_exists($vendorPath)) {
                        throw new \Exception('Stripe library not found. Run: composer require stripe/stripe-php');
                    }
                    require_once $vendorPath;
                }
                
                \Stripe\Stripe::setApiKey('sk_test_AkIyLORA5oDaKdLxUc3gUdwN');
                
                $token = \Stripe\Token::retrieve($stripeToken);
                $card = $token->card;
                
                // Update user with card info
                $userData = [
                    'stripe_card_id' => $card->id,
                    'stripe_last4' => $card->last4,
                    'stripe_brand' => $card->brand,
                    'stripe_exp_month' => $card->exp_month,
                    'stripe_exp_year' => $card->exp_year,
                    'stripe_card_name' => $cardName,
                ];
                
                $this->userModel->update($userId, $userData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Thêm thẻ thành công',
                    'card' => [
                        'id' => $card->id,
                        'last4' => $card->last4,
                        'brand' => $card->brand,
                        'exp_month' => $card->exp_month,
                        'exp_year' => $card->exp_year,
                        'name' => $cardName,
                    ]
                ]);
            } else {
                // MoMo linking - for demo, just save the phone
                $userData = [
                    'momo_phone' => $momoPhone,
                    'momo_name' => $cardName,
                ];
                
                $this->userModel->update($userId, $userData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Liên kết MoMo thành công',
                    'momo' => [
                        'phone' => $momoPhone,
                        'name' => $cardName,
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }
    
    public function removeCard()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ]);
        }

        $userId = session()->get('user_id');
        $cardType = $this->request->getPost('card_type') ?? 'stripe';
        
        $userData = [];
        $message = '';
        
        if ($cardType === 'stripe') {
            $userData = [
                'stripe_card_id' => null,
                'stripe_last4' => null,
                'stripe_brand' => null,
                'stripe_exp_month' => null,
                'stripe_exp_year' => null,
                'stripe_card_name' => null,
            ];
            $message = 'Xóa thẻ tín dụng thành công';
        } elseif ($cardType === 'momo') {
            // Remove MoMo info
            $userData = [
                'momo_phone' => null,
                'momo_name' => null,
            ];
            $message = 'Xóa liên kết MoMo thành công';
        }
        
        $this->userModel->update($userId, $userData);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    public function invoices()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $invoices = $this->invoiceModel->getUserInvoices($userId);

        $data = [
            'title' => 'Hóa đơn',
            'invoices' => $invoices,
            'tab' => 'invoices',
        ];

        return view('frontend/profile/invoices', $data);
    }
}
