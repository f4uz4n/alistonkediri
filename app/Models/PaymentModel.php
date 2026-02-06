<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'participant_payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'participant_id', 'amount', 'proof', 'status', 'payment_date', 'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getInstallments($participant_id)
    {
        return $this->where('participant_id', $participant_id)
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }

    public function getTotalPaid($participant_id)
    {
        return $this->where('participant_id', $participant_id)
            ->where('status', 'verified')
            ->selectSum('amount')
            ->first();
    }
}
