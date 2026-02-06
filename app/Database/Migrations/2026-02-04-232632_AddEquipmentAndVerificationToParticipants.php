<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEquipmentAndVerificationToParticipants extends Migration
{
    public function up()
    {
        // Add is_verified and verified_at to participants
        $this->forge->addColumn('participants', [
            'is_verified' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'status'
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'is_verified'
            ],
            'verified_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'verified_at'
            ],
        ]);

        // Create participant_equipment table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'participant_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'item_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'collected'],
                'default' => 'pending',
            ],
            'collected_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'collected_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addForeignKey('participant_id', 'participants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('participant_equipment');
    }

    public function down()
    {
        $this->forge->dropTable('participant_equipment');
        $this->forge->dropColumn('participants', ['is_verified', 'verified_at', 'verified_by']);
    }
}
