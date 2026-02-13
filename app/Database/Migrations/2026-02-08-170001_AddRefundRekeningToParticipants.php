<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRefundRekeningToParticipants extends Migration
{
    public function up()
    {
        $this->forge->addColumn('participants', [
            'refund_rekening' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'cancellation_notes',
            ],
            'refund_bank_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => true,
                'after'      => 'refund_rekening',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('participants', ['refund_rekening', 'refund_bank_name']);
    }
}
