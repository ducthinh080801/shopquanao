<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ReviewModel;
use App\Models\ViewsLogModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected $productModel;
    protected $reviewModel;
    protected $viewsLogModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
        $this->viewsLogModel = new ViewsLogModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $category_id = $this->request->getGet('category_id');
        $min_price = $this->request->getGet('min_price');
        $max_price = $this->request->getGet('max_price');
        $sort = $this->request->getGet('sort');
        $q = trim($this->request->getGet('q'));

        $globalMinPrice = $this->productModel->where('is_active', 1)->selectMin('price')->get()->getRowArray()['price'] ?? 0;
        $globalMaxPrice = $this->productModel->where('is_active', 1)->selectMax('price')->get()->getRowArray()['price'] ?? 10000000;

        $builder = $this->productModel->where('is_active', 1);

        if (!empty($q)) {
            $builder->like('name', $q);
        }

        if (!empty($category_id)) {
            $builder->where('category_id', $category_id);
        }

        if (!empty($min_price)) {
            $builder->where('price >=', $min_price);
        }
        if (!empty($max_price)) {
            $builder->where('price <=', $max_price);
        }

        switch ($sort) {
            case 'price_asc':
                $builder->orderBy('price', 'ASC');
                break;
            case 'price_desc':
                $builder->orderBy('price', 'DESC');
                break;
            case 'newest':
                $builder->orderBy('created_at', 'DESC');
                break;
            case 'best_selling':
                $builder->orderBy('sold_count', 'DESC');
                break;
            default:
                $builder->orderBy('created_at', 'DESC');
        }

        $products = $builder->paginate(12);

        $data = [
            'title' => !empty($q) ? 'Kết quả tìm kiếm cho "' . $q . '"' : 'Sản phẩm',
            'products' => $products,
            'pager' => $this->productModel->pager,
            'categories' => $this->categoryModel->getActiveCategories(),
            'category_id' => $category_id,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'sort' => $sort,
            'q' => $q,
            'global_min_price' => $globalMinPrice,
            'global_max_price' => $globalMaxPrice,
            'total_results' => count($products),
        ];

        return view('frontend/products/index', $data);
    }

    public function category($slug)
    {
        $category = $this->categoryModel->where('slug', $slug)->first();
        
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $category['name'],
            'category' => $category,
            'products' => $this->productModel
                ->where('category_id', $category['id'])
                ->where('is_active', 1)
                ->paginate(12),
            'pager' => $this->productModel->pager,
            'categories' => $this->categoryModel->getActiveCategories(),
        ];

        return view('frontend/products/category', $data);
    }

    public function detail($slug)
    {
        $product = $this->productModel->getProductWithCategory($slug);
        
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();
        $userId = session()->get('user_id');
        
        $this->viewsLogModel->logView($product['id'], $userId, $ipAddress, $userAgent);
        $this->productModel->incrementViews($product['id']);

        $reviews = $this->reviewModel->getProductReviews($product['id']);
        $relatedProducts = $this->productModel
            ->where('category_id', $product['category_id'])
            ->where('id !=', $product['id'])
            ->where('is_active', 1)
            ->findAll(4);

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'reviews' => $reviews,
            'related_products' => $relatedProducts,
        ];

        return view('frontend/products/detail', $data);
    }

    public function addReview()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để đánh giá'
            ]);
        }

        $productId = $this->request->getPost('product_id');
        $rating = $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');

        $reviewData = [
            'product_id' => $productId,
            'user_id' => session()->get('user_id'),
            'rating' => $rating,
            'comment' => $comment,
            'is_approved' => 0,
        ];

        if ($this->reviewModel->insert($reviewData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cảm ơn bạn đã đánh giá. Đánh giá của bạn đang chờ duyệt.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Có lỗi xảy ra, vui lòng thử lại'
        ]);
    }
}
