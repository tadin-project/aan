<?php

namespace App\Controllers;

use App\Controllers\MyController;
use App\Models\DashboardModel;
use App\Models\ListDeviceModel;

class Dashboard extends MyController
{
    protected $M_ms_device;
    protected $M_dashboard;
    protected $list_device;
    private $ld_id;

    public function __construct()
    {
        parent::__construct();
        $this->M_dashboard = new DashboardModel();

        $this->list_device = new ListDeviceModel();

        $this->ld_id = $this->list_device->where('ld_status', 1)->findAll()[0]->ld_id;
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        $data['ld_id'] = $this->ld_id;
        return $this->base_theme('v_dashboard', $data);
    }

    public function get_data_dashboard()
    {
        $res = [
            'status' => true,
            'data' => [
                'ld_kompas' => 0,
                'dd_lat' => "0",
                'dd_long' => "0",
            ],
            'msg' => '',
        ];

        $ld_id = $this->request->getVar('ld_id');

        $ld_data = $this->list_device->find($ld_id);

        if ($ld_data) {
            $res['data']['ld_kompas'] = $ld_data->ld_kompas;
        }

        $dd_data = $this->M_dashboard->get_marker($ld_id);

        if ($dd_data) {
            $res['data']['dd_lat'] = $dd_data->dd_lat;
            $res['data']['dd_long'] = $dd_data->dd_long;
        }

        return $this->response->setStatusCode(200)->setJSON($res);
    }
}
