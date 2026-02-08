<?php

namespace App\Models;

use CodeIgniter\Model;

class TestimonialModel extends Model
{
    protected $table = 'testimonials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'package_id', 'testimonial', 'rating', 'status', 'source', 'agency_id',
        'verified_at', 'verified_by', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Daftar testimoni yang sudah diverifikasi (untuk tampil di depan).
     */
    public function getVerifiedList($limit = 20)
    {
        return $this->select('testimonials.*, travel_packages.name as package_name')
            ->join('travel_packages', 'travel_packages.id = testimonials.package_id', 'left')
            ->where('testimonials.status', 'verified')
            ->orderBy('testimonials.verified_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Semua testimoni untuk admin (dengan filter status).
     */
    public function getListForAdmin($filters = [])
    {
        $builder = $this->select('testimonials.*, travel_packages.name as package_name')
            ->join('travel_packages', 'travel_packages.id = testimonials.package_id', 'left')
            ->orderBy('testimonials.created_at', 'DESC');

        if (!empty($filters['status'])) {
            $builder->where('testimonials.status', $filters['status']);
        }
        return $builder->findAll();
    }

    /**
     * Testimoni dari agency (untuk menu agency).
     */
    public function getListForAgency($agency_id)
    {
        return $this->select('testimonials.*, travel_packages.name as package_name')
            ->join('travel_packages', 'travel_packages.id = testimonials.package_id', 'left')
            ->where('testimonials.agency_id', $agency_id)
            ->orderBy('testimonials.created_at', 'DESC')
            ->findAll();
    }
}
