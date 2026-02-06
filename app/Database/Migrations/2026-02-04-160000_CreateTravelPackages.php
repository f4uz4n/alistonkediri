<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTravelPackages extends Migration
{
    public function up()
    {
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
            'departure_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'duration' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'location_start_end' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'hotel_mekkah' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'hotel_mekkah_stars' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
            ],
            'hotel_madinah' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'hotel_madinah_stars' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
            ],
            'airline' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'flight_route' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'price_unit' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'JT',
            ],
            'inclusions' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'freebies' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'branch_info' => [
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
        $this->forge->createTable('travel_packages');
    }

    public function down()
    {
        $this->forge->dropTable('travel_packages');
    }
}
