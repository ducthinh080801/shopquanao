<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'product_id', 'user_id', 'order_id', 'rating', 'comment', 'is_approved'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'product_id' => 'required|integer',
        'user_id' => 'required|integer',
        'rating' => 'required|integer|greater_than[0]|less_than[6]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getProductReviews($productId, $approvedOnly = true)
    {
        $builder = $this->select('reviews.*, users.full_name, users.avatar')
            ->join('users', 'users.id = reviews.user_id')
            ->where('reviews.product_id', $productId);

        if ($approvedOnly) {
            $builder->where('reviews.is_approved', 1);
        }

        return $builder->orderBy('reviews.created_at', 'DESC')->findAll();
    }

    public function getPendingReviews()
    {
        return $this->select('reviews.*, users.full_name, products.name as product_name')
            ->join('users', 'users.id = reviews.user_id')
            ->join('products', 'products.id = reviews.product_id')
            ->where('reviews.is_approved', 0)
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();
    }

    public function getUserReviews($userId)
    {
        return $this->select('reviews.*, products.name as product_name, products.image')
            ->join('products', 'products.id = reviews.product_id')
            ->where('reviews.user_id', $userId)
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();
    }
}
