<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\DataModel;

class Data extends BaseController
{
    protected $M_data_device;

    public function __construct()
    {
        $this->M_data_device = new DataModel();
    }

    public function store()
    {
        $data = [
            'v1'        => addslashes($this->request->getVar('v1')),
            'v2'        => addslashes($this->request->getVar('v2')),
            'v3'        => addslashes($this->request->getVar('v3')),
            'i1'        => addslashes($this->request->getVar('i1')),
            'i2'        => addslashes($this->request->getVar('i2')),
            'i3'        => addslashes($this->request->getVar('i3')),
            'w1'        => addslashes($this->request->getVar('w1')),
            'w2'        => addslashes($this->request->getVar('w2')),
            'w3'        => addslashes($this->request->getVar('w3')),
            'kwh'       => addslashes($this->request->getVar('kwh')),
            'pf1'       => addslashes($this->request->getVar('pf1')),
            'pf2'       => addslashes($this->request->getVar('pf2')),
            'pf3'       => addslashes($this->request->getVar('pf3')),
            'magnitude' => addslashes($this->request->getVar('magnitude')),
            'timer'     => addslashes($this->request->getVar('timer')),
            'waktu'     => $this->request->getVar('waktu'),
            'output'    => addslashes($this->request->getVar('output')),
            'kondisi'   => addslashes($this->request->getVar('kondisi')),
            'gangguan'  => addslashes($this->request->getVar('gangguan')),
            'record_at' => addslashes($this->request->getVar('record_at')),
        ];

        $res = $this->M_data_device->save($data);

        if ($res) {
            return $this->response->setStatusCode(200)->setJSON(['msg' => 'Data berhasil ditambahkan!']);
        } else {
            return $this->response->setStatusCode(400)->setJSON(['msg' => 'Data gagal ditambahkan!']);
        }
    }
}
