<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MsUserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_name'         => "root",
                'user_fullname'     => "Admin Vendor",
                'user_email'        => "root@gmail.com",
                'user_status'       => 1,
                'is_superuser'      => 1,
                'password'          => password_hash('adminvendor', PASSWORD_DEFAULT),
            ], [
                'user_name'         => "admin",
                'user_fullname'     => "Administrator",
                'user_email'        => "admin@gmail.com",
                'user_status'       => 1,
                'is_superuser'      => 0,
                'password'          => password_hash('admin', PASSWORD_DEFAULT),
            ], [
                'user_name'         => "user1",
                'user_fullname'     => "User 1",
                'user_email'        => "user@gmail.com",
                'user_status'       => 1,
                'is_superuser'      => 0,
                'password'          => password_hash('user', PASSWORD_DEFAULT),
            ],
        ];

        $this->db->table('ms_user')->insertBatch($data);
    }
}
