<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPassportPlaceOfBirth extends Migration
{
    public function up()
    {
        $this->forge->addColumn('participants', [
            'passport_place_of_birth' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'passport_full_name',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', 'passport_place_of_birth');
    }
}
