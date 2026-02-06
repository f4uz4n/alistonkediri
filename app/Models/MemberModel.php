<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id';
    protected $allowedFields = ['agency_id', 'name', 'email', 'phone', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
