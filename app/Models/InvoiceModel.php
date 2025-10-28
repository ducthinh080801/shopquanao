<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'payment_id', 'invoice_number', 'pdf_path', 'invoice_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'payment_id' => 'required|integer',
        'invoice_number' => 'required|is_unique[invoices.invoice_number,id,{id}]',
        'invoice_date' => 'required',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getInvoiceByOrderId($orderId)
    {
        return $this->where('order_id', $orderId)->first();
    }

    public function getInvoiceByNumber($invoiceNumber)
    {
        return $this->select('invoices.*, orders.*, users.full_name as customer_name, users.email as customer_email, users.phone as customer_phone, users.address as customer_address')
            ->join('orders', 'orders.id = invoices.order_id')
            ->join('users', 'users.id = orders.user_id')
            ->where('invoices.invoice_number', $invoiceNumber)
            ->first();
    }

    public function getUserInvoices($userId)
    {
        return $this->select('invoices.*, orders.order_number, orders.total_amount, orders.status')
            ->join('orders', 'orders.id = invoices.order_id')
            ->where('orders.user_id', $userId)
            ->orderBy('invoices.created_at', 'DESC')
            ->findAll();
    }
}
