<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTitleToParticipantDocuments extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('title', 'participant_documents')) {
            $this->forge->addColumn('participant_documents', [
                'title' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'type',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('title', 'participant_documents')) {
            $this->forge->dropColumn('participant_documents', 'title');
        }
    }
}
