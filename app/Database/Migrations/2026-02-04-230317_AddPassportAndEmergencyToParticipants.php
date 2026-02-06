<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPassportAndEmergencyToParticipants extends Migration
{
    public function up()
    {
        $fields = [
            'passport_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'nationality',
            ],
            'passport_issuance_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'passport_number',
            ],
            'passport_expiry_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'passport_issuance_date',
            ],
            'passport_issuance_city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'passport_expiry_date',
            ],
            'emergency_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'passport_issuance_city',
            ],
            'emergency_relationship' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'emergency_name',
            ],
            'emergency_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'emergency_relationship',
            ],
        ];
        $this->forge->addColumn('participants', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', [
            'passport_number', 'passport_issuance_date', 'passport_expiry_date',
            'passport_issuance_city', 'emergency_name', 'emergency_relationship', 'emergency_phone'
        ]);
    }
}
