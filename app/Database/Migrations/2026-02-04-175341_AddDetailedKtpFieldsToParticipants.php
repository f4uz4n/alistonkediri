<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailedKtpFieldsToParticipants extends Migration
{
    public function up()
    {
        $fields = [
            'nik' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'after' => 'agency_id',
            ],
            'place_of_birth' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'after' => 'name',
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'place_of_birth',
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Laki-laki', 'Perempuan'],
                'after' => 'date_of_birth',
            ],
            'address' => [
                'type' => 'TEXT',
                'after' => 'gender',
            ],
            'rt_rw' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'after' => 'address',
            ],
            'kelurahan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'after' => 'rt_rw',
            ],
            'kecamatan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'after' => 'kelurahan',
            ],
            'kabupaten' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'after' => 'kecamatan',
            ],
            'provinsi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'after' => 'kabupaten',
            ],
            'religion' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'after' => 'provinsi',
            ],
            'marital_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'after' => 'religion',
            ],
            'occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'after' => 'marital_status',
            ],
            'blood_type' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
                'after' => 'occupation',
            ],
            'nationality' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'WNI',
                'after' => 'blood_type',
            ],
        ];
        $this->forge->addColumn('participants', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', [
            'nik', 'place_of_birth', 'date_of_birth', 'gender', 'address',
            'rt_rw', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi',
            'religion', 'marital_status', 'occupation', 'blood_type', 'nationality'
        ]);
    }
}
