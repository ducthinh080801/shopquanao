<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'category_id', 'name', 'slug', 'description', 'price', 'discount_price',
        'stock', 'image', 'images', 'sizes', 'colors', 'views', 'sold_count',
        'rating_avg', 'rating_count', 'is_featured', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'category_id' => 'required|integer',
        'name' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|alpha_dash|is_unique[products.slug,id,{id}]',
        'price' => 'required|decimal',
        'stock' => 'required|integer',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getProductWithCategory($slug)
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.slug', $slug)
            ->where('products.is_active', 1)
            ->first();
    }

    public function getFeaturedProducts($limit = 8)
    {
        return $this->where('is_featured', 1)
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function getBestSellers($limit = 8)
    {
        return $this->where('is_active', 1)
            ->orderBy('sold_count', 'DESC')
            ->findAll($limit);
    }

    public function getMostViewed($limit = 8)
    {
        return $this->where('is_active', 1)
            ->orderBy('views', 'DESC')
            ->findAll($limit);
    }

    public function getTopRated($limit = 8)
    {
        return $this->where('is_active', 1)
            ->where('rating_count >', 0)
            ->orderBy('rating_avg', 'DESC')
            ->findAll($limit);
    }

    public function getLowStock($threshold = 5)
    {
        return $this->where('is_active', 1)
            ->where('stock <=', $threshold)
            ->orderBy('stock', 'ASC')
            ->findAll();
    }

    public function searchProducts($keyword, $filters = [])
    {
        $builder = $this->where('is_active', 1);

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('description', $keyword)
                ->groupEnd();
        }

        if (!empty($filters['category_id'])) {
            $builder->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['min_price'])) {
            $builder->where('price >=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $builder->where('price <=', $filters['max_price']);
        }

        if (!empty($filters['color'])) {
            $builder->like('colors', $filters['color']);
        }

        if (!empty($filters['size'])) {
            $builder->like('sizes', $filters['size']);
        }

        return $builder->findAll();
    }

    public function incrementViews($productId)
    {
        $this->set('views', 'views + 1', false)
            ->where('id', $productId)
            ->update();
    }

    public function updateRating($productId)
    {
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->where('product_id', $productId)
            ->where('is_approved', 1)
            ->findAll();

        if (count($reviews) > 0) {
            $totalRating = array_sum(array_column($reviews, 'rating'));
            $avgRating = $totalRating / count($reviews);

            $this->update($productId, [
                'rating_avg' => round($avgRating, 2),
                'rating_count' => count($reviews)
            ]);
        }
    }
}
