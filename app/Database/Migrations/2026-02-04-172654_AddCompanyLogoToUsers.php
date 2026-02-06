<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyLogoToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'company_logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'profile_pic'
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'company_logo');
    }
}
