<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    public function get_sidebar($user_id, $parent_id = 0)
    {
        $sql = "SELECT
                    distinct mm.menu_id ,
                    mm.menu_nama ,
                    mm.menu_url ,
                    mm.menu_ikon ,
                    coalesce(child.total, 0) as total
                from
                    ms_menu mm
                inner join group_menu gm on
                    gm.menu_id = mm.menu_id
                inner join group_user gu on
                    gu.group_id = gm.group_id
                left join (
                    select
                        count(*) as total,
                        menu_parent_id
                    from
                        ms_menu
                    group by
                        menu_parent_id 
                    ) child on
                    child.menu_parent_id = mm.menu_id
                where
                    gu.user_id = $user_id
                    and mm.menu_status = 1
                    and mm.menu_parent_id = $parent_id
                order by
                    mm.menu_kode";
        $result = $this->db->query($sql)->getResult();

        $res = "";

        foreach ($result as $key => $value) {
            if ($value->total > 0) {
                $res .= '<li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="' . (!empty($value->menu_ikon) ? $value->menu_ikon : 'far fa-circle nav-icon') . '"></i>
                                <p>
                                    ' . $value->menu_nama . '
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                ' . $this->get_sidebar($user_id, $value->menu_id) . '
                            </ul>
                        </li>';
            } else {
                $res .= '<li class="nav-item">
                            <a href="' . base_url() . '/' . $value->menu_url . '" class="nav-link">
                                <i class="' . (!empty($value->menu_ikon) ? $value->menu_ikon : 'far fa-circle nav-icon') . '"></i>
                                <p>' . $value->menu_nama . '</p>
                            </a>
                        </li>';
            }
        }

        return $res;
    }
}
