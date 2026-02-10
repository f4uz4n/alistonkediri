<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNomorRekeningDanBankToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'nomor_rekening' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'address',
            ],
            'nama_bank' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'nomor_rekening',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['nomor_rekening', 'nama_bank']);
    }
}
