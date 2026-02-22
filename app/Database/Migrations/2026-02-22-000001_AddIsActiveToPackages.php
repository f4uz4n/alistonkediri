<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('travel_packages', [
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'branch_info',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('travel_packages', 'is_active');
    }
}
