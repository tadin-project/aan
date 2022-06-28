<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\DataDeviceModel;
use App\Models\ListDeviceModel;
use Config\Database;

class Data extends BaseController
{
    private $data_device;
    private $list_device;
    private $db;

    public function __construct()
    {
        $this->data_device = new DataDeviceModel();
        $this->list_device = new ListDeviceModel();
        $this->db = Database::connect();
    }

    public function check()
    {
        $device_kode = $this->request->getVar('device_kode');
        $data = $this->list_device->where('ld_kode', $device_kode)->findAll();

        if (count($data) > 0) {
            return $data[0]->ld_id;
        } else {
            return 0;
        }
    }

    public function store()
    {
        $ld_id = $this->check();
        if ($ld_id == 0) return $this->response->setStatusCode(400, 'Device Unknown!');

        $dd_lat =  addslashes($this->request->getVar('lat'));
        $dd_long =  addslashes($this->request->getVar('long'));
        $ld_kompas =  addslashes($this->request->getVar('kompas'));

        $data_dd = [
            'ld_id' => $ld_id,
            'dd_lat' => $dd_lat ? $dd_lat : 0,
            'dd_long' => $dd_long ? $dd_long : 0,
        ];

        $res = $this->data_device->insert($data_dd);

        if (!$res) return $this->response->setStatusCode(400, 'Data gagal ditambahkan!');

        $data_ld = [
            'ld_id' => $ld_id,
            'ld_kompas' => $ld_kompas ? $ld_kompas : 0,
        ];

        $res = $this->list_device->save($data_ld);
        if (!$res) return $this->response->setStatusCode(400, 'Data Kompas gagal diperbarui!');

        $sql = "SELECT
                    concat(ld_up, ',', ld_right, ',', ld_bottom, ',', ld_left) as btn
                from
                    list_device ld
                where
                    ld.ld_id = $ld_id";

        $ld_tombol = $this->db->query($sql)->getRow()->btn;

        // return $this->response->setStatusCode(201, $ld_tombol);
        echo $ld_tombol;
    }

    public function update_device()
    {
        $ld_id = $this->request->getVar('ld_id');
        $ld_position = $this->request->getVar('ld_position');
        $ld_value = $this->request->getVar('ld_value');

        $data = [
            "ld_id" => $ld_id,
            "ld_" . $ld_position => $ld_value,
        ];

        $res = $this->list_device->save($data);

        if ($res) {
            return $this->response->setStatusCode(200)->setJSON([
                'status' => true,
                'data' => [
                    'ld_value' => $ld_value,
                    'data' => $data,
                ],
            ]);
        } else {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => false,
                'msg' => 'Gagal update. Silahkan hubungi admin',
            ]);
        }
    }
}
