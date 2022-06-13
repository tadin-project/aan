<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MsMenuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                // "menu_id" => 1,
                "menu_kode" => "01",
                "menu_nama" => "Master Umum",
                "menu_status" => 1,
                "menu_url" => "#",
                "menu_ikon" => "",
                "menu_parent_id" => 0,
            ], [
                // "menu_id" => 2,
                "menu_kode" => "01.01",
                "menu_nama" => "Master User",
                "menu_status" => 1,
                "menu_url" => "ms-user",
                "menu_ikon" => "",
                "menu_parent_id" => 1,
            ], [
                // "menu_id" => 3,
                "menu_kode" => "01.02",
                "menu_nama" => "Master Group",
                "menu_status" => 1,
                "menu_url" => "ms-group",
                "menu_ikon" => "",
                "menu_parent_id" => 1,
            ], [
                // "menu_id" => 4,
                "menu_kode" => "00",
                "menu_nama" => "Dashboard",
                "menu_status" => 1,
                "menu_url" => "dashboard",
                "menu_ikon" => "",
                "menu_parent_id" => 0,
            ], [
                // "menu_id" => 5,
                "menu_kode" => "01.03",
                "menu_nama" => "Setting",
                "menu_status" => 1,
                "menu_url" => "setting",
                "menu_ikon" => "",
                "menu_parent_id" => 1,
            ],
        ];

        $this->db->table('ms_menu')->insertBatch($data);
    }
}
