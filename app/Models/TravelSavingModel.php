<?php

namespace App\Models;

use CodeIgniter\Model;

class TravelSavingModel extends Model
{
    protected $table = 'travel_savings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'agency_id', 'name', 'nik', 'phone', 'total_balance', 'status',
        'package_id', 'participant_id', 'claimed_at', 'notes'
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getWithAgency()
    {
        return $this->select('travel_savings.*, users.full_name as agency_name')
            ->join('users', 'users.id = travel_savings.agency_id')
            ->orderBy('travel_savings.created_at', 'DESC');
    }
}
