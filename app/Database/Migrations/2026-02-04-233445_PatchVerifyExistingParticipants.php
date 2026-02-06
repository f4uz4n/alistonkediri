<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PatchVerifyExistingParticipants extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->table('participants')->update([
            'status' => 'verified',
            'is_verified' => true,
            'verified_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function down()
    {
    // No roll back needed for data patch
    }
}
