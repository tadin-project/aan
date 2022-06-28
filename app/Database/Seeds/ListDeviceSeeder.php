<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ListDeviceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                "ld_id" => 1,
                "ld_kode" => "abcdef",
                "ld_nama" => "kapal 1",
            ],
        ];

        $this->db->table("list_device")->insertBatch($data);
    }
}
