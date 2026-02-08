<?php

namespace App\Models;

use CodeIgniter\Model;

class TravelSavingDepositModel extends Model
{
    protected $table = 'travel_savings_deposits';
    protected $primaryKey = 'id';
    protected $allowedFields = ['travel_saving_id', 'amount', 'payment_date', 'proof', 'status', 'notes'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getBySaving($travelSavingId)
    {
        return $this->where('travel_saving_id', $travelSavingId)
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }

    public function getTotalVerified($travelSavingId)
    {
        $r = $this->where('travel_saving_id', $travelSavingId)
            ->where('status', 'verified')
            ->selectSum('amount')
            ->first();
        return (float)($r['amount'] ?? 0);
    }
}
