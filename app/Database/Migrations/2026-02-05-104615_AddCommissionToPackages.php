<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCommissionToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('travel_packages', [
            'commission_per_pax' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'after' => 'price_unit'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('travel_packages', 'commission_per_pax');
    }
}
