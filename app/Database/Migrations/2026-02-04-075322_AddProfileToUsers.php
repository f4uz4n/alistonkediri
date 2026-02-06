<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'username'
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'full_name'
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'email'
            ],
            'profile_pic' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'phone'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['full_name', 'email', 'phone', 'profile_pic']);
    }
}
