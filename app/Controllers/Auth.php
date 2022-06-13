<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\MsUserModel;
use App\Models\SettingModel;

class Auth extends BaseController
{
    private $AuthModel;
    private $session_data;
    private $group_user_id = 3;
    protected $db;
    private $nama_website;

    public function __construct()
    {
        $this->session_data = session();
        $this->AuthModel = new AuthModel();
        $SettingModel = new SettingModel();
        $this->db = \Config\Database::connect();

        foreach ($SettingModel->where('setting_status', 1)->findAll() as $k => $v) {
            if ($v->setting_slug == 'nama_website') {
                $this->nama_website = $v->setting_value;
            }
        }
    }

    public function index()
    {
        $userdata = $this->session_data->get('userdata');
        if ($userdata && $userdata->is_login == 1) {
            $url = $this->AuthModel->get_first_menu($userdata->user_id);
            return redirect()->to(base_url() . "/$url");
        }
        $data = [
            'title' => $this->nama_website,
        ];
        echo view('auth/v_login', $data);
    }

    public function register()
    {
        $userdata = $this->session_data->get('userdata');
        if ($userdata && $userdata->is_login == 1) {
            $url = $this->AuthModel->get_first_menu($userdata->user_id);
            return redirect()->to(base_url() . "/$url");
        }
        $data = [
            'title' => $this->nama_website,
        ];
        echo view('auth/v_register', $data);
    }

    public function forgot_password()
    {
        $userdata = $this->session_data->get('userdata');
        if ($userdata && $userdata->is_login == 1) {
            $url = $this->AuthModel->get_first_menu($userdata->user_id);
            return redirect()->to(base_url() . "/$url");
        }
        $data = [
            'title' => $this->nama_website,
        ];
        echo view('auth/v_forgot_password', $data);
    }

    public function reset_password()
    {
        $userdata = $this->session_data->get('userdata');
        if ($userdata && $userdata->is_login == 1) {
            $url = $this->AuthModel->get_first_menu($userdata->user_id);
            return redirect()->to(base_url() . "/$url");
        }

        $pr  = $this->db->table('password_reset')->where('pr_token', $this->request->getVar('token'))->get()->getRow();
        if ($pr) {
            $data = [
                'title' => $this->nama_website,
                'user_id' => $pr->user_id,
            ];
            // $data['user'] = $this->db->table('ms_user')->where('user_id', $pr->user_id)->get()->getRowArray();
            echo view('auth/v_reset_password', $data);
        } else {
            return redirect()->to(base_url() . "/forgot-password");
        }
    }

    public function login_proses()
    {
        $res = [
            'status' => false,
            'message' => 'Gagal Login'
        ];

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $userdata = $this->AuthModel->get_user_data($username);

        if ($userdata) {
            if ($userdata->user_status == 1) {
                if (password_verify($password, $userdata->password)) {
                    $session = session();

                    unset($userdata->password);
                    $userdata->is_login = 1;

                    $sess_data = [
                        'userdata' => $userdata,
                    ];

                    $session->set($sess_data);

                    $url = $this->AuthModel->get_first_menu($userdata->user_id);

                    $res = [
                        'status' => true,
                        'message' => 'Berhasil!',
                        'url' => base_url() . "/$url",
                    ];
                } else {
                    $res = [
                        'status' => false,
                        'message' => 'Password Salah!'
                    ];
                }
            } else {
                $res = [
                    'status' => false,
                    'message' => 'User Tidak Aktif!'
                ];
            }
        } else {
            $res = [
                'status' => false,
                'message' => 'User Belum Terdaftar!'
            ];
        }

        echo json_encode($res);
    }

    public function register_proses()
    {
        $res = [
            'status' => false,
            'message' => ''
        ];

        $user_name = $this->request->getVar('user_name');
        $user_email = $this->request->getVar('user_email');
        $password = $this->request->getVar('password');

        $user = new MsUserModel();

        $userdata = $user
            ->where('user_name', $user_name)
            ->orWhere('user_email', $user_email)
            ->first();
        $res['userdata'] = $userdata;

        if (!$userdata) {
            // insert user
            $data = [
                'user_name' => $user_name,
                'user_email' => $user_email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'user_status' => 1,
                'is_superuser' => 0,
            ];

            $respond = $user->save($data);

            if ($respond) {
                // insert group_user
                $respond = $this->db->table('group_user')->insert([
                    'user_id' => $user->getInsertID(),
                    'group_id' => $this->group_user_id,
                ]);
                if ($respond) {
                    $res = [
                        'status' => true,
                        'msg' => "Pendaftaran berhasil, silahkan login",
                        'url' => base_url() . '/',
                    ];
                } else {
                    $res = [
                        'status' => false,
                        'msg' => "Gagal mendaftarkan user. Hubungi admin untuk info lebih lanjut",
                    ];
                }
            } else {
                $res = [
                    'status' => false,
                    'msg' => "Gagal mendaftarkan user. Hubungi admin untuk info lebih lanjut",
                ];
            }
        } else {
            $res = [
                'status' => false,
                'message' => 'Username atau email telah digunakan. Silahkan gunakan yang lain!'
            ];
        }

        return $this->response->setStatusCode(200)->setJSON($res);
    }

    function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url() . '/');
    }

    public function forgot_proses()
    {
        $res = [
            'status' => false,
            'message' => ''
        ];

        $user_email = $this->request->getVar('user_email');
        $user = $this->db->table('ms_user')->where('user_email', $user_email)->get()->getRow();
        $expire = intval($this->db->table('setting')->where('setting_slug', 'range_expire')->get()->getRow()->setting_value);
        if ($user) {
            $token = substr(sha1(rand()), 0, 30);
            $data = [
                'user_id' => $user->user_id,
                'pr_token' => $token,
                'pr_expire' => $expire,
            ];

            $status = $this->db->table('password_reset')->insert($data);

            if ($status > 0) {
                $res = [
                    'status' => true,
                    'url' => base_url() . '/reset-password?token=' . $token
                ];
            } else {
                $res = [
                    'status' => false,
                    'message' => 'Gagal reset password'
                ];
            }
        } else {
            $res = [
                'status' => false,
                'message' => 'Email belum terdaftar'
            ];
        }

        return $this->response->setStatusCode(200)->setJSON($res);
    }

    public function reset_proses()
    {
        $res = [
            'status' => false,
            'message' => ''
        ];

        $user_id = $this->request->getVar('user_id');
        $password = $this->request->getVar('password');
        $user = $this->db->table('ms_user')->where('user_id', $user_id)->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        if ($user) {
            $status = $this->db->table('password_reset')->where('user_id', $user_id)->delete();

            if ($status > 0) {
                $res = [
                    'status' => true,
                    'url' => base_url() . '/'
                ];
            } else {
                $res = [
                    'status' => false,
                    'message' => 'Gagal hapus token'
                ];
            }

            $res['user_id'] = $status;
        } else {
            $res = [
                'status' => false,
                'message' => 'Gagal update password'
            ];
        }

        return $this->response->setStatusCode(200)->setJSON($res);
    }
}
