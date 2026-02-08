<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpgradeFieldsToParticipants extends Migration
{
    public function up()
    {
        $fields = [
            'hotel_upgrade_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'package_id',
            ],
            'room_upgrade_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'hotel_upgrade_id',
            ],
            'upgrade_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'after' => 'room_upgrade_id',
            ],
        ];

        $this->forge->addColumn('participants', $fields);

    // Add Foreign Keys (optional but good practice, keeping it simple for now as per previous style)
    // $this->forge->addForeignKey('hotel_upgrade_id', 'travel_hotels', 'id', 'SET NULL', 'CASCADE');
    // $this->forge->addForeignKey('room_upgrade_id', 'travel_hotel_rooms', 'id', 'SET NULL', 'CASCADE');
    // $this->forge->processIndexes('participants'); 
    }

    public function down()
    {
        $this->forge->dropColumn('participants', ['hotel_upgrade_id', 'room_upgrade_id', 'upgrade_cost']);
    }
}
