<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedKantorPusatUser extends Migration
{
    public function up()
    {
        $exists = $this->db->table('users')->where('username', 'kantor_pusat')->countAllResults();
        if ($exists > 0) {
            return;
        }
        $password = password_hash('kantor_pusat_' . time(), PASSWORD_DEFAULT);
        $this->db->table('users')->insert([
            'username'   => 'kantor_pusat',
            'password'   => $password,
            'role'       => 'agency',
            'full_name'  => 'Registrasi Kantor',
            'email'      => null,
            'phone'      => null,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->db->table('users')->where('username', 'kantor_pusat')->delete();
    }
}
