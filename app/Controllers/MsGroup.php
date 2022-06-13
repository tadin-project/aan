<?php

namespace App\Controllers;

use App\Controllers\MyController;
use App\Models\MsGroupModel;

class MsGroup extends MyController
{
    protected $M_ms_group;
    public function __construct()
    {
        parent::__construct();
        $this->M_ms_group = new MsGroupModel();
    }

    public function index()
    {
        $data['title'] = "Master Group";
        $data['modul'] = $this->M_ms_group->get_modul();
        return $this->base_theme('v_ms_group', $data);
    }

    public function get_data()
    {
        $columns = array(
            'mg.group_id',
            'mg.group_kode',
            'mg.group_nama',
            'mg.group_status',
            'mg.group_ket',
            'coalesce(child.total, 0) total',
        );
        $search = $this->request->getVar('search')['value'];
        $where = "";
        if (isset($search) && $search != "") {
            $where = "AND (";
            for ($i = 0; $i < count($columns); $i++) {
                if ($i != 5) {
                    $where .= " LOWER(" . $columns[$i] . ") LIKE LOWER('%" . ($search) . "%') OR ";
                }
            }
            $where = substr_replace($where, "", -3);
            $where .= ')';
        }
        $iTotalRecords = intval($this->M_ms_group->get_total($where));
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
        $data = $this->M_ms_group->get_data($limit, $where, $order, $columns);
        $no   = 1 + $start;
        foreach ($data as $row) {
            $isi = rawurlencode(json_encode($row));
            $btn_akses = 'btn-secondary';
            if ($row->total > 0) {
                $btn_akses = 'btn-warning';
            }

            if ($row->group_status == 1) {
                $status = '<span class="badge rounded-pill badge-success">Aktif</span>';
            } else {
                $status = '<span class="badge rounded-pill badge-danger">Non Aktif</span>';
            }

            $hapus = ' <button onclick="set_del(\'' . $row->group_id . '\')" class="btn btn-sm btn-danger " title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>';


            $action = '<div class="d-grid gap-2 d-md-block">';

            if ($row->group_id == 1) {
                if ($this->userdata->user_id == 1) {
                    $action .= ' <button onclick="akses(\'' . $row->group_id . '\',\'' . $row->group_nama . '\')" class="btn btn-sm ' . $btn_akses . ' font-weight-bold" title="Hak Akses">
                                        <i class="fa fa-cogs"></i>
                                    </button>
                                    <button onclick="set_val(\'' . $isi . '\')" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>';
                } else {
                    $action .= '';
                }
            } else {
                $action .= ' <button onclick="akses(\'' . $row->group_id . '\',\'' . $row->group_nama . '\')" class="btn btn-sm ' . $btn_akses . ' font-weight-bold" title="Hak Akses">
                                    <i class="fa fa-cogs"></i>
                                </button>
                                <button onclick="set_val(\'' . $isi . '\')" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>' . ($row->group_id > 2 ? $hapus : '');
            }

            $action .= '</div>';

            // $isHaveAkses = $this->M_ms_group->checked_have_akses($row->GRP_ID);
            // if ($isHaveAkses > 0) {
            //     $hak = '<a href="grup/akses/' . $id2 . '" class="btn btn-xs btn-warning" title="Pengaturan">
            // 				<i class="fa fa-cogs"></i>
            // 			</a>';
            // } else {
            //     $hak = '<a href="grup/akses/' . $id2 . '" class="btn btn-xs btn-default" title="Pengaturan">
            // 				<i class="fa fa-cogs"></i>
            // 			</a>';
            // }

            $records["data"][] = array(
                $no++,
                $row->group_kode,
                $row->group_nama,
                $status,
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
        $act = $this->request->getVar('act');

        $data = [
            'group_kode' => addslashes($this->request->getVar('group_kode')),
            'group_nama' => addslashes($this->request->getVar('group_nama')),
            'group_ket' => addslashes($this->request->getVar('group_ket')),
            'group_status' => $this->request->getVar('group_status'),
        ];

        if ($act == 'add') {
            $res = $this->M_ms_group->insert($data);
        } else {
            $id = $this->request->getVar('group_id');
            $res = $this->M_ms_group->update($id, $data);
        }

        if ($res > 0) {
            $response = [
                'status' => true,
                'message' => $act == 'add' ? 'Berhasil menambahkan data!' : 'Berhasil memperbarui data!',
                'title' => 'Success',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => $act == 'add' ? 'Gagal menambahkan data!' : 'Gagal memperbarui data!',
                'title' => 'Error',
            ];
        }

        echo json_encode($response);
    }

    public function hapus($id)
    {
        $res = $this->M_ms_group->delete($id);

        $response = [
            'status' => false,
            'message' => "Data Gagal dihapus"
        ];

        if ($res) {
            $response = [
                'status' => true,
                'message' => "Data Berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }

    public function get_menu()
    {
        $group_id = $this->request->getVar('group_id');
        $modul_id = $this->request->getVar('modul_id');

        $group_id = !empty($group_id) ? $group_id : 0;

        $q = $this->M_ms_group->get_menu($modul_id, $group_id);
        $res = [];

        if (count($q) > 0) {
            foreach ($q as $key => $value) {
                // $value->class = ($value->total_child <= 0 && $value->is_selected == 1) ? 'jstree-checked' : '';
                $res[$key] = [
                    "id" => $value->menu_id,
                    "parent" => $value->menu_parent_id == 0 ? "#" : $value->menu_parent_id,
                    "text" => $value->menu_nama,
                    "state" => [
                        "opened" => true,
                        "selected" => ($value->total_child <= 0 && $value->is_selected == 1) ? true : false,
                        "checked" => ($value->total_child <= 0 && $value->is_selected == 1) ? true : false,
                    ],
                    "li_attr"     => $value,
                    "a_attr"      => $value,
                ];
            }
        }

        echo json_encode($res);
    }

    public function save_akses()
    {

        $modul_id = $this->request->getVar('modul_id');
        $group_id = $this->request->getVar('group_id');
        $menu_id = !empty($this->request->getVar('menu_id')) ? $this->request->getVar('menu_id') : [];
        $data = [];

        if (count($menu_id) > 0) {
            foreach ($menu_id as $v) {
                $data[] = [
                    'group_id' => $group_id,
                    'modul_id' => $modul_id,
                    'menu_id' => $v
                ];
            }
        }

        $res = $this->M_ms_group->delete_akses($modul_id, $group_id);
        if ($res['status'] && count($data) > 0) {
            $res = $this->M_ms_group->save_akses($data);
        }

        echo json_encode($res);
    }
}
