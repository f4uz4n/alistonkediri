<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAgencyCommissions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'agency_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'package_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'amount_calculated' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
            ],
            'amount_final' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid'],
                'default' => 'pending',
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('agency_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'travel_packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('agency_commissions');
    }

    public function down()
    {
        $this->forge->dropTable('agency_commissions');
    }
}
