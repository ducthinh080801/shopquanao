<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Trang chủ - Shop Quần Áo',
            'featured_products' => $this->productModel->getFeaturedProducts(8),
            'best_sellers' => $this->productModel->getBestSellers(8),
            'most_viewed' => $this->productModel->getMostViewed(8),
            'categories' => $this->categoryModel->getActiveCategories(),
        ];

        return view('frontend/home/index', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        $categoryId = $this->request->getGet('category');
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        $color = $this->request->getGet('color');
        $size = $this->request->getGet('size');

        $filters = [
            'category_id' => $categoryId,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'color' => $color,
            'size' => $size,
        ];

        $products = $this->productModel->searchProducts($keyword, $filters);
        $categories = $this->categoryModel->getActiveCategories();

        $data = [
            'title' => 'Tìm kiếm: ' . $keyword,
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword,
            'filters' => $filters,
        ];

        return view('frontend/home/search', $data);
    }
}
