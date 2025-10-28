<?php

namespace App\Models;

use CodeIgniter\Model;

class ViewsLogModel extends Model
{
    protected $table = 'views_log';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'product_id', 'user_id', 'ip_address', 'user_agent', 'viewed_at'
    ];

    // No timestamps for this table
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'product_id' => 'required|integer',
        'ip_address' => 'required',
        'viewed_at' => 'required',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function logView($productId, $userId = null, $ipAddress, $userAgent)
    {
        return $this->insert([
            'product_id' => $productId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'viewed_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getProductViewCount($productId)
    {
        return $this->where('product_id', $productId)->countAllResults();
    }

    public function getRecentViews($limit = 100)
    {
        return $this->select('views_log.*, products.name as product_name')
            ->join('products', 'products.id = views_log.product_id')
            ->orderBy('viewed_at', 'DESC')
            ->findAll($limit);
    }
}
