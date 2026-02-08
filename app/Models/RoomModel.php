<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'travel_hotel_rooms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'hotel_id', 'name', 'type', 'price_per_pax', 'facilities', 'image'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'hotel_id' => 'required|integer',
        'name' => 'required|min_length[3]',
        'type' => 'required',
        'price_per_pax' => 'required|decimal',
    ];

    public function getRoomsByHotel($hotelId)
    {
        return $this->where('hotel_id', $hotelId)->findAll();
    }
}
