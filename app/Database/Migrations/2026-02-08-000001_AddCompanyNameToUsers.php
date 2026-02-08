<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyNameToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'company_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'company_logo',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'company_name');
    }
}
