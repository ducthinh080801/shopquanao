<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'order_number', 'total_amount', 'status',
        'shipping_address', 'shipping_phone', 'shipping_name', 'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'order_number' => 'required|is_unique[orders.order_number,id,{id}]',
        'total_amount' => 'required|decimal',
        'shipping_address' => 'required',
        'shipping_phone' => 'required',
        'shipping_name' => 'required',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getOrderWithItems($orderId)
    {
        $order = $this->find($orderId);
        
        if ($order) {
            $orderItemModel = new OrderItemModel();
            $order['items'] = $orderItemModel->where('order_id', $orderId)->findAll();
            
            $userModel = new UserModel();
            $order['user'] = $userModel->find($order['user_id']);
        }
        
        return $order;
    }

    public function getOrderByNumber($orderNumber)
    {
        $order = $this->where('order_number', $orderNumber)->first();
        
        if ($order) {
            $orderItemModel = new OrderItemModel();
            $order['items'] = $orderItemModel->where('order_id', $order['id'])->findAll();
        }
        
        return $order;
    }

    public function getUserOrders($userId, $limit = null)
    {
        $builder = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC');
        
        if ($limit) {
            return $builder->findAll($limit);
        }
        
        return $builder->findAll();
    }

    public function getRecentOrders($limit = 10)
    {
        return $this->select('orders.*, users.full_name, users.email')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->findAll($limit);
    }

    public function getTotalRevenue($startDate = null, $endDate = null)
    {
        $builder = $this->selectSum('total_amount')
            ->where('status !=', 'cancelled');

        if ($startDate) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate) {
            $builder->where('created_at <=', $endDate);
        }

        $result = $builder->get()->getRowArray();
        return $result['total_amount'] ?? 0;
    }

    public function getOrdersByStatus($status)
    {
        return $this->where('status', $status)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
