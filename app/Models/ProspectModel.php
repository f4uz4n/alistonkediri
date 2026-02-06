<?php

namespace App\Models;

use CodeIgniter\Model;

class ProspectModel extends Model
{
    protected $table = 'prospects';
    protected $primaryKey = 'id';
    protected $allowedFields = ['agency_id', 'name', 'phone', 'email', 'interest', 'status', 'notes', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
