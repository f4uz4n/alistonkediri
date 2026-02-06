<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImageToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('travel_packages', [
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'name'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('travel_packages', 'image');
    }
}
