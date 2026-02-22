<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKKToParticipantDocumentsType extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE participant_documents MODIFY COLUMN type ENUM('passport', 'id_card', 'vaccine', 'kk', 'other') DEFAULT 'other'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE participant_documents MODIFY COLUMN type ENUM('passport', 'id_card', 'vaccine', 'other') DEFAULT 'other'");
    }
}
