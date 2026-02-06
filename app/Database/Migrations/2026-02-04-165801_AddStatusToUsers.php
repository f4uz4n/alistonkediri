<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'after' => 'role'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_active');
    }
}
