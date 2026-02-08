<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFacilitiesAndImageToHotelsAndRooms extends Migration
{
    public function up()
    {
        $this->forge->addColumn('travel_hotels', [
            'facilities' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'address',
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'facilities',
            ],
        ]);

        $this->forge->addColumn('travel_hotel_rooms', [
            'facilities' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'price_per_pax',
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'facilities',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('travel_hotels', ['facilities', 'image']);
        $this->forge->dropColumn('travel_hotel_rooms', ['facilities', 'image']);
    }
}
