<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'setting_kode' => "01",
                'setting_nama' => "Nama Website",
                'setting_value' => "Master",
                'setting_slug' => "nama_website",
                'setting_status' => 1,
            ], [
                'setting_kode' => "02",
                'setting_nama' => "Email Forgot Password",
                'setting_value' => "admin@gmail.com",
                'setting_slug' => "email_forgot_password",
                'setting_status' => 1,
            ], [
                'setting_kode' => "03",
                'setting_nama' => "Jangka Waktu Expire Token Forgot Password(detik)",
                'setting_value' => "300",
                'setting_slug' => "range_expire",
                'setting_status' => 1,
            ], [
                'setting_kode' => "04",
                'setting_nama' => "Ikon Judul Website",
                'setting_value' => "<i class=\"fas fa-laugh-wink\"></i>",
                'setting_slug' => "ikon_website",
                'setting_status' => 1,
            ],
        ];

        $this->db->table("setting")->insertBatch($data);
    }
}
