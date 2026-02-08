<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHotelIdsToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('travel_packages', [
            'hotel_mekkah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'hotel_mekkah_stars',
            ],
            'hotel_madinah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'hotel_madinah_stars',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('travel_packages', ['hotel_mekkah_id', 'hotel_madinah_id']);
    }
}
