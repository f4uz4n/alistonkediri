<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCancellationFieldsToParticipants extends Migration
{
    public function up()
    {
        $this->forge->addColumn('participants', [
            'cancelled_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'after'   => 'boarded_at',
            ],
            'refund_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'after'      => 'cancelled_at',
            ],
            'cancellation_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'refund_amount',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', ['cancelled_at', 'refund_amount', 'cancellation_notes']);
    }
}
