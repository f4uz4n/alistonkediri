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

    /**
     * Semua setoran pending (dari agency) beserta data tabungan dan nama agensi.
     */
    public function getPendingWithSavingAndAgency()
    {
        return $this->select('travel_savings_deposits.*, travel_savings.name as saving_name, travel_savings.nik as saving_nik, travel_savings.agency_id, users.full_name as agency_name')
            ->join('travel_savings', 'travel_savings.id = travel_savings_deposits.travel_saving_id')
            ->join('users', 'users.id = travel_savings.agency_id')
            ->where('travel_savings_deposits.status', 'pending')
            ->orderBy('travel_savings_deposits.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Hitung setoran per agency: pending dan verified.
     */
    public function getCountsByAgency($agencyId)
    {
        $builder = $this->join('travel_savings', 'travel_savings.id = travel_savings_deposits.travel_saving_id')
            ->where('travel_savings.agency_id', $agencyId);
        $pending = (clone $builder)->where('travel_savings_deposits.status', 'pending')->countAllResults();
        $verified = (clone $builder)->where('travel_savings_deposits.status', 'verified')->countAllResults();
        return ['pending' => $pending, 'verified' => $verified];
    }
}
