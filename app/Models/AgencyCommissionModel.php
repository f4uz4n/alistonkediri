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
        return $this->select('agency_commissions.*, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.departure_date, travel_packages.commission_per_pax as rate')
            ->join('users', 'users.id = agency_commissions.agency_id')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->orderBy('travel_packages.departure_date', 'DESC')
            ->orderBy('agency_commissions.created_at', 'DESC')
            ->findAll();
    }

    public function getFilteredCommissions($filters = [])
    {
        $builder = $this->select('agency_commissions.*, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.departure_date, travel_packages.commission_per_pax as rate')
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

        if (!empty($filters['departure_date'])) {
            $builder->where('travel_packages.departure_date', $filters['departure_date']);
        }

        return $builder->orderBy('travel_packages.departure_date', 'DESC')->orderBy('agency_commissions.created_at', 'DESC')->findAll();
    }

    /**
     * Daftar komisi pending per tanggal pemberangkatan (untuk verifikasi bulk).
     */
    public function getPendingByDepartureDate($departure_date)
    {
        return $this->select('agency_commissions.*, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.departure_date, travel_packages.commission_per_pax as rate')
            ->join('users', 'users.id = agency_commissions.agency_id')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->where('travel_packages.departure_date', $departure_date)
            ->where('agency_commissions.status', 'pending')
            ->orderBy('users.full_name')
            ->findAll();
    }

    /**
     * Daftar tanggal pemberangkatan yang punya komisi (untuk filter & dropdown).
     */
    public function getDepartureDatesWithCommissions()
    {
        $db = \Config\Database::connect();
        return $db->table('agency_commissions')
            ->select('travel_packages.departure_date')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->groupBy('travel_packages.departure_date')
            ->orderBy('travel_packages.departure_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ringkasan komisi per tanggal pemberangkatan (akumulasi).
     */
    public function getSummaryByDepartureDate()
    {
        $db = \Config\Database::connect();
        return $db->table('agency_commissions')
            ->select('travel_packages.departure_date, COUNT(agency_commissions.id) as total_rows, SUM(agency_commissions.amount_final) as total_commission, SUM(CASE WHEN agency_commissions.status = "pending" THEN 1 ELSE 0 END) as pending_count, MAX(agency_commissions.paid_at) as last_verified_at')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->groupBy('travel_packages.departure_date')
            ->orderBy('travel_packages.departure_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Semua komisi per tanggal pemberangkatan (untuk modal rincian).
     */
    public function getCommissionsByDepartureDate($departure_date)
    {
        return $this->select('agency_commissions.*, users.full_name as agency_name, travel_packages.name as package_name, travel_packages.departure_date, travel_packages.commission_per_pax as rate')
            ->join('users', 'users.id = agency_commissions.agency_id')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->where('travel_packages.departure_date', $departure_date)
            ->orderBy('users.full_name')
            ->findAll();
    }

    /**
     * Komisi yang sudah diverifikasi (paid) untuk satu agency — untuk dashboard & laporan penghasilan.
     * Filter: start_date, end_date (berdasarkan paid_at), package_id.
     */
    public function getPaidCommissionsForAgency($agency_id, $filters = [])
    {
        $builder = $this->select('agency_commissions.*, travel_packages.name as package_name, travel_packages.departure_date')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->where('agency_commissions.agency_id', $agency_id)
            ->where('agency_commissions.status', 'paid');

        if (!empty($filters['start_date'])) {
            $builder->where('agency_commissions.paid_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('agency_commissions.paid_at <=', $filters['end_date'] . ' 23:59:59');
        }
        if (!empty($filters['package_id'])) {
            $builder->where('agency_commissions.package_id', $filters['package_id']);
        }

        return $builder->orderBy('agency_commissions.paid_at', 'DESC')->findAll();
    }

    /**
     * Semua komisi untuk satu agency (pending + paid) — untuk laporan penghasilan dengan kolom status.
     * Filter: start_date, end_date (paid pakai paid_at, pending pakai created_at), package_id.
     */
    public function getCommissionsForAgency($agency_id, $filters = [])
    {
        $builder = $this->select('agency_commissions.*, travel_packages.name as package_name, travel_packages.departure_date')
            ->join('travel_packages', 'travel_packages.id = agency_commissions.package_id')
            ->where('agency_commissions.agency_id', $agency_id);

        if (!empty($filters['package_id'])) {
            $builder->where('agency_commissions.package_id', $filters['package_id']);
        }
        $start = !empty($filters['start_date']) ? $filters['start_date'] . ' 00:00:00' : null;
        $end = !empty($filters['end_date']) ? $filters['end_date'] . ' 23:59:59' : null;
        if ($start || $end) {
            $builder->groupStart()
                ->groupStart()
                ->where('agency_commissions.status', 'paid');
            if ($start) $builder->where('agency_commissions.paid_at >=', $start);
            if ($end) $builder->where('agency_commissions.paid_at <=', $end);
            $builder->groupEnd()
                ->orGroupStart()
                ->where('agency_commissions.status', 'pending');
            if ($start) $builder->where('agency_commissions.created_at >=', $start);
            if ($end) $builder->where('agency_commissions.created_at <=', $end);
            $builder->groupEnd()
                ->groupEnd();
        }

        return $builder->orderBy('agency_commissions.updated_at', 'DESC')->findAll();
    }

    /**
     * Total komisi paid untuk satu agency (untuk dashboard / filter).
     */
    public function getTotalPaidCommissionForAgency($agency_id, $filters = [])
    {
        $builder = $this->where('agency_id', $agency_id)->where('status', 'paid');
        if (!empty($filters['start_date'])) {
            $builder->where('paid_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('paid_at <=', $filters['end_date'] . ' 23:59:59');
        }
        if (!empty($filters['package_id'])) {
            $builder->where('package_id', $filters['package_id']);
        }
        $row = $builder->selectSum('amount_final', 'total')->get()->getRowArray();
        return (float)($row['total'] ?? 0);
    }

    /**
     * Total komisi belum dibayar (pending) untuk satu agency.
     */
    public function getTotalPendingCommissionForAgency($agency_id, $filters = [])
    {
        $builder = $this->where('agency_id', $agency_id)->where('status', 'pending');
        if (!empty($filters['start_date'])) {
            $builder->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }
        if (!empty($filters['package_id'])) {
            $builder->where('package_id', $filters['package_id']);
        }
        $row = $builder->selectSum('amount_final', 'total')->get()->getRowArray();
        return (float)($row['total'] ?? 0);
    }
}
