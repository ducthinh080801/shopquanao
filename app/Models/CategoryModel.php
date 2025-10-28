<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'slug', 'description', 'image', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'slug' => 'required|alpha_dash|is_unique[categories.slug,id,{id}]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getActiveCategories()
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function getCategoryWithProducts($slug)
    {
        $category = $this->where('slug', $slug)->first();
        
        if ($category) {
            $productModel = new ProductModel();
            $category['products'] = $productModel->where('category_id', $category['id'])
                ->where('is_active', 1)
                ->findAll();
        }
        
        return $category;
    }
}
