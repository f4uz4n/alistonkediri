<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNamaSekretarisBendaharaToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'nama_sekretaris_bendahara' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => true,
                'after'      => 'tanggal_sk_perijinan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'nama_sekretaris_bendahara');
    }
}
