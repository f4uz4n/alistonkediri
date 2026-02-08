<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBoardingToParticipants extends Migration
{
    public function up()
    {
        $fields = [
            'is_boarded' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'upgrade_cost',
            ],
            'boarded_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'is_boarded',
            ],
        ];

        $this->forge->addColumn('participants', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', ['is_boarded', 'boarded_at']);
    }
}
