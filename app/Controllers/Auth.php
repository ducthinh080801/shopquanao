<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data = ['title' => 'Đăng nhập'];
        return view('frontend/auth/login', $data);
    }

    public function loginPost()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ]);
        }

        if (!$user['is_active']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa'
            ]);
        }

        // Set session
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ];
        session()->set($sessionData);

        $redirectUrl = $user['role'] === 'admin' ? '/admin/dashboard' : '/';

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'redirect' => $redirectUrl
        ]);
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data = ['title' => 'Đăng ký'];
        return view('frontend/auth/register', $data);
    }

    public function registerPost()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'full_name' => 'required|min_length[3]',
            'phone' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vui lòng kiểm tra lại thông tin',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'role' => 'user',
            'is_active' => 1,
        ];

        if ($this->userModel->insert($userData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đăng ký thành công! Vui lòng đăng nhập.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra, vui lòng thử lại'
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Đăng xuất thành công');
    }

    public function forgotPassword()
    {
        $data = ['title' => 'Quên mật khẩu'];
        return view('frontend/auth/forgot_password', $data);
    }

    public function forgotPasswordPost()
    {
        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email không tồn tại trong hệ thống'
            ]);
        }

        // TODO: Send reset password email
        // For now, just return success message
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Link đặt lại mật khẩu đã được gửi đến email của bạn'
        ]);
    }
}
