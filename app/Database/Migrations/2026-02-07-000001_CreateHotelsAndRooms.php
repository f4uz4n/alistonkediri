<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHotelsAndRooms extends Migration
{
    public function up()
    {
        // Hotels Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '100', // Mekkah / Madinah
            ],
            'star_rating' => [
                'type' => 'INT',
                'constraint' => 1,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('travel_hotels');

        // Rooms Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'hotel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100', // e.g. "Standard Quad", "Deluxe Double"
            ],
            'type' => [
                'type' => 'VARCHAR', // Quad, Triple, Double
                'constraint' => '50',
            ],
            'price_per_pax' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00
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
        $this->forge->addForeignKey('hotel_id', 'travel_hotels', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('travel_hotel_rooms');
    }

    public function down()
    {
        $this->forge->dropTable('travel_hotel_rooms');
        $this->forge->dropTable('travel_hotels');
    }
}
