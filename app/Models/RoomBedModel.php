<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomBedModel extends Model
{
    protected $table = 'travel_hotel_room_beds';
    protected $primaryKey = 'id';
    protected $allowedFields = ['room_id', 'name', 'price'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getByRoom($roomId)
    {
        return $this->where('room_id', $roomId)->orderBy('name')->findAll();
    }
}
