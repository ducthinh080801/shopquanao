<?php

namespace App\Models;

use CodeIgniter\Model;

class SavedCardModel extends Model
{
    protected $table = 'saved_cards';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'stripe_card_id', 'last4', 'brand', 'exp_month', 'exp_year', 'name', 'is_default'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'stripe_card_id' => 'required',
        'last4' => 'required|exact_length[4]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getUserCards($userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getDefaultCard($userId)
    {
        return $this->where('user_id', $userId)
            ->where('is_default', 1)
            ->first();
    }

    public function setDefaultCard($userId, $cardId)
    {
        // Unset all default cards
        $this->where('user_id', $userId)
            ->set(['is_default' => 0])
            ->update();

        // Set new default
        return $this->update($cardId, ['is_default' => 1]);
    }
}
