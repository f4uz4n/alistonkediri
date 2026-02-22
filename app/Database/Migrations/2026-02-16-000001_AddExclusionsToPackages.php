<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExclusionsToPackages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('travel_packages', [
            'exclusions' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'freebies',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('travel_packages', 'exclusions');
    }
}
