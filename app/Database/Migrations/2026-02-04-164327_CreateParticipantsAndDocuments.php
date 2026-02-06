<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParticipantsAndDocuments extends Migration
{
    public function up()
    {
        // Participants Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'agency_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'verified', 'cancelled'],
                'default' => 'pending',
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
        $this->forge->addForeignKey('package_id', 'travel_packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('agency_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('participants');

        // Documents Table
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
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['passport', 'id_card', 'vaccine', 'other'],
                'default' => 'other',
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'is_verified' => [
                'type' => 'BOOLEAN',
                'default' => false,
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
        $this->forge->createTable('participant_documents');
    }

    public function down()
    {
        $this->forge->dropTable('participant_documents', true);
        $this->forge->dropTable('participants', true);
    }
}
