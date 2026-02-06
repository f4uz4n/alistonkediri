<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeToMaterials extends Migration
{
    public function up()
    {
        $this->forge->addColumn('materials', [
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['file', 'youtube', 'url'],
                'default'    => 'file',
                'after'      => 'id'
            ],
            'url' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'file_path'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('materials', ['type', 'url']);
    }
}
