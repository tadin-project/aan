<?php

namespace App\Models;

use CodeIgniter\Model;
use stdClass;

class DashboardModel extends Model
{
    public function get_marker($ld_id)
    {
        $sql = "SELECT
                    *
                from
                    data_device dd
                where
                    dd.ld_id = $ld_id
                order by
                    dd_id desc
                limit 1;";
        $res = $this->db->query($sql);

        if ($res) {
            $result = $res->getRow();
        } else {
            $result = new stdClass();
            $result->dd_lat = 0;
            $result->dd_long = 0;
        }

        return $result;
    }
}
