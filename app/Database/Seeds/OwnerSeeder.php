<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OwnerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'owner',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role'     => 'owner',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'agency',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role'     => 'agency',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($data);
    }
}
