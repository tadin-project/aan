<?php

namespace App\Controllers;

use App\Controllers\MyController;

class Profil extends MyController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!($this->userdata && $this->userdata->is_login == 1)) {
            return redirect()->to(base_url() . '/');
        }
        $id = $this->userdata->user_id;
        $data['user'] = $this->db->table('ms_user')->where('user_id', $id)->get()->getRow();
        $data['title'] = "Profil";
        return $this->base_theme('v_profil', $data);
    }

    public function update_pass()
    {
        $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);

        $data = [
            'password' => $password,
        ];

        $id = $this->request->getVar('user_id');
        $res = $this->db->table('ms_user')->where('user_id', $id)->update($data);

        if ($res > 0) {
            $response = [
                'status' => true,
                'message' => 'Berhasil memperbarui password!',
                'title' => 'Success',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Gagal memperbarui password!',
                'title' => 'Error',
            ];
        }

        echo json_encode($response);
    }

    public function update_user()
    {
        $user_id = $this->request->getVar('user_id');
        $user_fullname = $this->request->getVar('user_fullname');

        $data = [
            'user_fullname' => $user_fullname,
        ];

        $res = $this->db->table('ms_user')->where('user_id', $user_id)->update($data);

        $userdata = $this->userdata;
        $userdata->user_fullname = $user_fullname;

        $session = session();
        $session->remove('userdata');
        $session->set([
            'userdata' => $userdata,
        ]);

        if ($res > 0) {
            $response = [
                'status' => true,
                'message' => 'Berhasil memperbarui data user!',
                'title' => 'Success',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Gagal memperbarui data user!',
                'title' => 'Error',
            ];
        }

        echo json_encode($response);
    }
}
