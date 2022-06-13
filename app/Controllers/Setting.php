<?php

namespace App\Controllers;

use App\Controllers\MyController;
use App\Models\SettingModel;

class Setting extends MyController
{
    protected $M_setting;
    public function __construct()
    {
        parent::__construct();
        $this->M_setting = new SettingModel();
    }

    public function index()
    {
        $data['title'] = "Master Setting";
        return $this->base_theme('v_setting', $data);
    }

    public function get_data()
    {
        $columns = array(
            'setting_id',
            'setting_kode',
            'setting_nama',
            'setting_value',
        );

        $colSearch = [
            'setting_kode',
            'setting_nama',
        ];

        $search = $this->request->getVar('search')['value'];
        $where = "";
        if (isset($search) && $search != "") {
            $where = "AND (";
            for ($i = 0; $i < count($colSearch); $i++) {
                $where .= " LOWER(" . $colSearch[$i] . ") LIKE LOWER('%" . ($search) . "%') OR ";
            }
            $where = substr_replace($where, "", -3);
            $where .= ')';
        }
        $iTotalRecords = intval($this->M_setting->get_total($where));
        $length = intval($this->request->getVar('length'));
        $length = $length < 0 ? $iTotalRecords : $length;
        $start  = intval($this->request->getVar('start'));
        $draw      = intval($_REQUEST['draw']);
        $sortCol0 = $this->request->getVar('order')[0];
        $records = array();
        $records["data"] = array();
        $order = "";
        if (isset($start) && $length != '-1') {
            $limit = "limit " . intval($start) . ", " . intval($length);
        }

        if (isset($sortCol0)) {
            $order = "ORDER BY  ";
            for ($i = 0; $i < count($this->request->getVar('order')); $i++) {
                if ($this->request->getVar('columns')[intval($this->request->getVar('order')[$i]['column'])]['orderable'] == "true") {
                    $order .= "" . $columns[intval($this->request->getVar('order')[$i]['column'])] . " " .
                        ($this->request->getVar('order')[$i]['dir'] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $order = substr_replace($order, "", -2);
            if ($order == "ORDER BY") {
                $order = "";
            }
        }
        $data = $this->M_setting->get_data($limit, $where, $order, $columns);
        $no   = 1 + $start;
        foreach ($data as $row) {
            $action = "";
            $isi = rawurlencode(json_encode($row));

            $action .= '<div class="d-grid gap-2 d-md-block">
                            <button onclick="set_val(\'' . $isi . '\')" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                        </div>';

            $records["data"][] = array(
                $no++,
                $row->setting_kode,
                $row->setting_nama,
                addslashes($row->setting_value),
                $action,
            );
        }

        $records["draw"] = $draw;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

    public function save()
    {
        $data = [
            'setting_value' => addslashes($this->request->getVar('setting_value')),
        ];

        $id = $this->request->getVar('setting_id');
        $res = $this->M_setting->update($id, $data);

        if ($res > 0) {
            $response = [
                'status' => true,
                'message' => 'Berhasil memperbarui data!',
                'title' => 'Success',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Gagal memperbarui data!',
                'title' => 'Error',
            ];
        }

        echo json_encode($response);
    }
}
