<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipantUpgradeBedModel extends Model
{
    protected $table = 'participant_upgrade_beds';
    protected $primaryKey = 'id';
    protected $allowedFields = ['participant_id', 'room_bed_id', 'qty'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Ambil pilihan bed upgrade jamaah: [room_bed_id => qty]
     */
    public function getQtyByParticipant($participantId)
    {
        $rows = $this->where('participant_id', $participantId)->findAll();
        $out = [];
        foreach ($rows as $r) {
            $out[(int) $r['room_bed_id']] = (int) $r['qty'];
        }
        return $out;
    }

    /**
     * Hapus semua bed upgrade jamaah lalu isi ulang.
     */
    public function replaceForParticipant($participantId, array $bedQty)
    {
        $this->where('participant_id', $participantId)->delete();
        foreach ($bedQty as $roomBedId => $qty) {
            $qty = (int) $qty;
            if ($roomBedId && $qty > 0) {
                $this->insert([
                    'participant_id' => $participantId,
                    'room_bed_id' => $roomBedId,
                    'qty' => $qty,
                ]);
            }
        }
    }
}
