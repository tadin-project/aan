<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupMenuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                "group_id" => 1,
                "menu_id" => 1,
            ], [
                "group_id" => 1,
                "menu_id" => 2,
            ], [
                "group_id" => 1,
                "menu_id" => 3,
            ], [
                "group_id" => 1,
                "menu_id" => 4,
            ], [
                "group_id" => 1,
                "menu_id" => 5,
            ], [
                "group_id" => 2,
                "menu_id" => 1,
            ], [
                "group_id" => 2,
                "menu_id" => 2,
            ], [
                "group_id" => 2,
                "menu_id" => 3,
            ], [
                "group_id" => 2,
                "menu_id" => 4,
            ], [
                "group_id" => 2,
                "menu_id" => 5,
            ], [
                "group_id" => 3,
                "menu_id" => 4,
            ],
        ];

        $this->db->table("group_menu")->insertBatch($data);
    }
}
