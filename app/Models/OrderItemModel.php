<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'product_id', 'product_name', 'price',
        'quantity', 'size', 'color', 'subtotal'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'product_id' => 'required|integer',
        'product_name' => 'required',
        'price' => 'required|decimal',
        'quantity' => 'required|integer',
        'subtotal' => 'required|decimal',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getOrderItems($orderId)
    {
        return $this->where('order_id', $orderId)->findAll();
    }
}
