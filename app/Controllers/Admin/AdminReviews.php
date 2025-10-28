<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\ProductModel;

class AdminReviews extends BaseController
{
    protected $reviewModel;
    protected $productModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $reviews = $this->reviewModel->select('reviews.*, users.full_name, products.name as product_name')
            ->join('users', 'users.id = reviews.user_id')
            ->join('products', 'products.id = reviews.product_id')
            ->orderBy('reviews.created_at', 'DESC')
            ->paginate(20);

        $data = [
            'title' => 'Quản lý đánh giá',
            'reviews' => $reviews,
            'pager' => $this->reviewModel->pager,
        ];

        return view('admin/reviews/index', $data);
    }

    public function pending()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $pendingReviews = $this->reviewModel->getPendingReviews();

        $data = [
            'title' => 'Đánh giá chờ duyệt',
            'reviews' => $pendingReviews,
        ];

        return view('admin/reviews/pending', $data);
    }

    public function approve($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $review = $this->reviewModel->find($id);
        
        if (!$review) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Review not found'
            ]);
        }

        if ($this->reviewModel->update($id, ['is_approved' => 1])) {
            // Update product rating
            $this->productModel->updateRating($review['product_id']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đã duyệt đánh giá'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }

    public function hide($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $review = $this->reviewModel->find($id);
        
        if (!$review) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Review not found'
            ]);
        }

        if ($this->reviewModel->update($id, ['is_approved' => 0])) {
            // Update product rating
            $this->productModel->updateRating($review['product_id']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đã ẩn đánh giá'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }

        $review = $this->reviewModel->find($id);
        
        if (!$review) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Review not found'
            ]);
        }

        if ($this->reviewModel->delete($id)) {
            // Update product rating
            $this->productModel->updateRating($review['product_id']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đã xóa đánh giá'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra'
        ]);
    }
}
