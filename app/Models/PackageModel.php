<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table = 'travel_packages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'image', 'departure_date', 'duration', 'location_start_end',
        'hotel_mekkah', 'hotel_mekkah_stars', 'hotel_madinah', 'hotel_madinah_stars',
        'airline', 'flight_route', 'price', 'price_unit', 'commission_per_pax',
        'inclusions', 'freebies', 'branch_info'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'price' => 'required|decimal',
    ];
}
