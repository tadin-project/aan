<?php

namespace App\Controllers;

use App\Controllers\MyController;
use App\Models\DashboardModel;

class Dashboard extends MyController
{
    protected $M_ms_device;
    protected $M_dashboard;

    public function __construct()
    {
        parent::__construct();
        $this->M_dashboard = new DashboardModel();
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        return $this->base_theme('v_dashboard', $data);
    }

    public function get_data_grafik()
    {
        $res = [
            'do' => [
                'data' => [],
                'labels' => [],
            ],
            'ph' => [
                'data' => [],
                'labels' => [],
            ],
            'suhu' => [
                'data' => [],
                'labels' => [],
            ],
            'turbidity' => [
                'data' => [],
                'labels' => [],
            ],
            'salinitas' => [
                'data' => [],
                'labels' => [],
            ],
        ];

        $db = \Config\Database::connect();
        $ld_id = $this->request->getVar("ld_id");
        $tgl_mulai = $this->request->getVar("tgl_mulai");
        $tgl_selesai = $this->request->getVar("tgl_selesai");

        $date_start = date("Y-m-d", strtotime($tgl_mulai));
        $date_finish = date("Y-m-d", strtotime($tgl_selesai));

        $limit = (strtotime($date_finish) - strtotime($date_start)) / (24 * 60 * 60) + 1;

        $limit = $limit <= 0 ? 0 : $limit;

        if (strtotime($date_finish) - strtotime($date_start) >= 0) {
            $where = "";
            $where .= " AND date(dd.created_at) between '$date_start' and '$date_finish'";
            $where .= " AND dd.ld_id = $ld_id ";

            $labels = [];
            $tmp = [];
            $tmp_data = [];
            for ($i = 0; $i < $limit; $i++) {
                $tgl =  date('d-m-Y', strtotime($date_finish) - ($limit - 1 - $i) * 24 * 60 * 60);
                $labels[] = $tgl;
                $tmp[date("Y-m-d", strtotime($tgl))] = $i;
                $tmp_data[] = 0;
            }

            $res = [
                'do' => [
                    'data' => $tmp_data,
                    'labels' => $labels,
                ],
                'ph' => [
                    'data' => $tmp_data,
                    'labels' => $labels,
                ],
                'suhu' => [
                    'data' => $tmp_data,
                    'labels' => $labels,
                ],
                'turbidity' => [
                    'data' => $tmp_data,
                    'labels' => $labels,
                ],
                'salinitas' => [
                    'data' => $tmp_data,
                    'labels' => $labels,
                ],
            ];

            $data = $db->query(
                "SELECT
                    sum(coalesce(do,0)) as nilai_do,
                    sum(coalesce(ph,0)) as nilai_ph,
                    sum(coalesce(suhu,0)) as nilai_suhu,
                    sum(coalesce(turbidity,0)) as nilai_turbidity,
                    sum(coalesce(salinitas,0)) as nilai_salinitas,
                    date(created_at) as dibuat
                from
                    data_device dd
                where
                    0 = 0
                    $where
                group by
                    dibuat"
            )->getResult();

            if (count($data) > 0) {
                foreach ($data as $k => $v) {
                    $res['do']['data'][$tmp[$v->dibuat]] = $v->nilai_do;
                    $res['ph']['data'][$tmp[$v->dibuat]] = $v->nilai_ph;
                    $res['suhu']['data'][$tmp[$v->dibuat]] = $v->nilai_suhu;
                    $res['turbidity']['data'][$tmp[$v->dibuat]] = $v->nilai_turbidity;
                    $res['salinitas']['data'][$tmp[$v->dibuat]] = $v->nilai_salinitas;
                }
            }
        }

        return $this->response->setStatusCode(200)->setJSON($res);
    }

    public function get_data()
    {
        $columns = array(
            'dd.ld_id',
            'dd.magnitude',
            'dd.timer',
            'dd.waktu',
            'dd.output',
            'dd.kondisi',
            "dd.gangguan",
        );

        $ld_id = $this->request->getVar('ld_id');
        $search = $this->request->getVar('search')['value'];
        $where = "";

        $where .= " AND dd.ld_id = $ld_id ";
        if (isset($search) && $search != "") {
            $where = "AND (";
            for ($i = 0; $i < count($columns); $i++) {
                $where .= " LOWER( cast( " . $columns[$i] . " as CHAR) ) LIKE LOWER('%" . ($search) . "%') OR ";
            }
            $where = substr_replace($where, "", -3);
            $where .= ')';
        }
        $iTotalRecords = intval($this->M_dashboard->get_total($where));
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
        $data = $this->M_dashboard->get_data($limit, $where, $order, $columns);
        $no   = 1 + $start;
        foreach ($data as $row) {
            $records["data"][] = array(
                $no++,
                $row->magnitude,
                $row->timer,
                $row->waktu,
                $row->output,
                $row->kondisi,
                $row->gangguan,
            );
        }

        $records["draw"] = $draw;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }
}
