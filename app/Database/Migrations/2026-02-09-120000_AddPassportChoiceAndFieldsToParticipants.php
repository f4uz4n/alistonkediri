<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPassportChoiceAndFieldsToParticipants extends Migration
{
    public function up()
    {
        $this->forge->addColumn('participants', [
            'has_passport' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => true,
                'after'      => 'nationality',
            ],
            'passport_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'passport_number',
            ],
            'passport_full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'passport_type',
            ],
            'passport_reg_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'passport_issuance_city',
            ],
            'passport_issuing_office' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'passport_reg_number',
            ],
            'passport_name_idn' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'passport_issuing_office',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', [
            'has_passport', 'passport_type', 'passport_full_name',
            'passport_reg_number', 'passport_issuing_office', 'passport_name_idn'
        ]);
    }
}
