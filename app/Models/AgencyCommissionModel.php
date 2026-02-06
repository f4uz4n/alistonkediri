<?php

namespace App\Models;

use CodeIgniter\Model;

class AgencyCommissionModel extends Model
{
    protected $table = 'agency_commissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'agency_id', 'package_id', 'amount_calculated', 'amount_final',
        'status', 'paid_at', 'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getSummary()
    {
        return $this->select('agency_commissions.*, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.commission_per_pax as rate')
            ->join('users', 'users.id = agency_commissions.agency_id')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->orderBy('agency_commissions.created_at', 'DESC')
            ->findAll();
    }

    public function getFilteredCommissions($filters = [])
    {
        $builder = $this->select('agency_commissions.*, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.commission_per_pax as rate')
            ->join('users', 'users.id = agency_commissions.agency_id')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id');

        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('users.full_name', $filters['search'])
                ->orLike('travel_packages.name', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['start_date'])) {
            $builder->where('agency_commissions.created_at >=', $filters['start_date'] . ' 00:00:00');
        }

        if (!empty($filters['end_date'])) {
            $builder->where('agency_commissions.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        if (!empty($filters['package_id'])) {
            $builder->where('agency_commissions.package_id', $filters['package_id']);
        }

        return $builder->orderBy('agency_commissions.created_at', 'DESC')->findAll();
    }
}
