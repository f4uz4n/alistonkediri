<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAddressToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'phone'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'address');
    }
}
