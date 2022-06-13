<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    public function get_user_data($username)
    {
        $user = new MsUserModel();
        $res = $user
            ->where("user_name", $username)
            ->first();

        return $res;
    }

    public function get_setting()
    {
        $setting = new SettingModel();
        $res = $setting
            ->where("setting_status", 1)->findAll();

        return $res;
    }

    public function get_first_menu($user_id)
    {
        $res = $this->db
            ->table('ms_menu mm')
            ->join('group_menu gm', 'mm.menu_id = gm.menu_id', 'inner')
            ->join('group_user gu', 'gm.group_id = gu.group_id', 'inner')
            ->where('gu.user_id', $user_id)
            ->where('mm.menu_url !=', '#')
            ->orderBy('mm.menu_kode', 'asc')
            ->get()
            ->getRow()->menu_url;

        return $res;
    }
}
