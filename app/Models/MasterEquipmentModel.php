<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterEquipmentModel extends Model
{
    protected $table = 'master_equipment';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'description',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
