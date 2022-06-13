<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Models\SettingModel;

class MyController extends BaseController
{
    protected $BaseModel;
    protected $session_data;
    protected $userdata;
    protected $setting;
    protected $nama_website;
    protected $ikon_website;

    public function __construct()
    {
        $this->session_data = session();
        $this->setting = new SettingModel();
        $this->userdata = $this->session_data->get('userdata');
        if (!($this->userdata && $this->userdata->is_login == 1)) {
            return redirect()->to(base_url() . '/');
        }

        foreach ($this->setting->where('setting_status', 1)->find() as  $v) {
            if ($v->setting_slug == 'nama_website') {
                $this->nama_website = $v->setting_value;
            } else if ($v->setting_slug == 'ikon_website') {
                $this->ikon_website = $v->setting_value;
            }
        }
        $this->BaseModel = new BaseModel();
    }

    public function base_theme($url, $data = [])
    {
        if (!($this->userdata && $this->userdata->is_login == 1)) {
            return redirect()->to(base_url() . '/');
        }

        $mainData = $data;
        $navbar = [
            'user_fullname' => $this->userdata->user_fullname,
            'user_id' => $this->userdata->user_id,
        ];

        $sidebarMenu = $this->BaseModel->get_sidebar($this->userdata->user_id, 1, 0);

        $sidebar = [
            'judul_web' => !empty($this->nama_website) ? $this->nama_website : "Website",
            'ikon_web' => !empty($this->ikon_website) ? $this->ikon_website : "<i class=\"fas fa-laugh-wink\"></i>",
            'sidebar' => $sidebarMenu,
        ];

        $mainData['title'] = $this->nama_website . (!empty($data['title']) ? " | " . $data['title'] : "");
        $mainData['navbar'] = view('template/navbar', $navbar);
        $mainData['sidebar'] = view('template/sidebar', $sidebar);
        $mainData['footer'] = [
            'judul' => $this->nama_website
        ];
        $mainData['content'] = view($url, $data);

        echo view('template/my_template', $mainData);
    }
}
