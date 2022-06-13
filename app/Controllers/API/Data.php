<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\DataRecordModel;

class Data extends BaseController
{
    protected $M_data_device;

    public function __construct()
    {
        $this->M_data_device = new DataRecordModel();
    }

    public function store()
    {
        $data = [
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
