<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoomBedsAndParticipantUpgradeBeds extends Migration
{
    public function up()
    {
        // Master bed/kasur per kamar (nama + harga)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'room_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('room_id', 'travel_hotel_rooms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('travel_hotel_room_beds');

        // Penambahan bed per jamaah saat upgrade (qty per tipe bed)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'participant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'room_bed_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'qty' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('participant_id', 'participants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('room_bed_id', 'travel_hotel_room_beds', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('participant_upgrade_beds');
    }

    public function down()
    {
        $this->forge->dropTable('participant_upgrade_beds', true);
        $this->forge->dropTable('travel_hotel_room_beds', true);
    }
}
