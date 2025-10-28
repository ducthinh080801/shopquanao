<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'user_id', 'payment_method', 'transaction_id',
        'amount', 'currency', 'status', 'stripe_payment_intent', 'payment_details'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'user_id' => 'required|integer',
        'transaction_id' => 'required|is_unique[payments.transaction_id,id,{id}]',
        'amount' => 'required|decimal',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getUserPayments($userId)
    {
        return $this->select('payments.*, orders.order_number')
            ->join('orders', 'orders.id = payments.order_id')
            ->where('payments.user_id', $userId)
            ->orderBy('payments.created_at', 'DESC')
            ->findAll();
    }

    public function getPaymentByTransaction($transactionId)
    {
        return $this->where('transaction_id', $transactionId)->first();
    }
}
