<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['type', 'title', 'description', 'file_path', 'url', 'created_by', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
