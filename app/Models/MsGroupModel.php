<?php

namespace App\Models;

use CodeIgniter\Model;

class MsGroupModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'ms_group';
    protected $primaryKey       = 'group_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'group_id',
        'group_kode',
        'group_nama',
        'group_status',
        'group_ket',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function get_total($where)
    {
        $sql = "SELECT
                    count(*) as total
                from
                    ms_group mg
                left join (
                    select
                        group_id,
                        count(menu_id) total
                    from
                        group_menu gm
                    group by
                        group_id) child on
                    child.group_id = mg.group_id
                where
                    0 = 0
                    $where";
        return $this->db->query($sql)->getRow()->total;
    }

    public function get_data($limit, $where, $order, $columns)
    {
        $slc = implode(',', $columns);
        $sql = "SELECT
                    $slc
                from
                    ms_group mg
                left join (
                    select
                        group_id,
                        count(menu_id) total
                    from
                        group_menu gm
                    group by
                        group_id) child on
                    child.group_id = mg.group_id
                where
                    0 = 0
                    $where
                $order $limit";
        return $this->db->query($sql)->getResult();
    }

    public function get_menu($group_id = 1)
    {
        $sql = "SELECT
                    mm.menu_id ,
                    concat(mm.menu_nama, case when mm.menu_status = 0 then ' (NON-AKTIF)' else '' end) as menu_nama,
                    mm.menu_parent_id,
                    case
                        when gm.group_id is null then 0
                        else 1
                    end as is_selected,
                    coalesce(child.total, 0) as total_child
                from
                    ms_menu mm
                left join group_menu gm on
                    gm.menu_id = mm.menu_id
                    and gm.group_id = $group_id
                left join (
                    select
                        count(*) as total,
                        menu_parent_id
                    from
                        ms_menu
                    group by
                        menu_parent_id 
                ) as child on
                    child.menu_parent_id = mm.menu_id
                order by
                    mm.menu_kode";

        return $this->db->query($sql)->getResult();
    }

    public function delete_akses($group_id)
    {
        $db = \Config\Database::connect();
        $group_menu = $db->table('group_menu');
        $result = $group_menu->delete(['group_id' => $group_id]);
        if ($result) {
            $res = [
                'status' => true,
                'message' => "Berhasil Memperbarui Akses",
            ];
        } else {
            $res = [
                'status' => false,
                'message' => "Gagal Menghapus Akses",
            ];
        }

        return $res;
    }

    public function save_akses($data)
    {
        $db = \Config\Database::connect();
        $group_menu = $db->table('group_menu');
        $result = $group_menu->insertBatch($data);
        if ($result) {
            $res = [
                'status' => true,
                'message' => "Berhasil Memperbarui Akses",
            ];
        } else {
            $res = [
                'status' => false,
                'message' => "Gagal Menambahkan Akses",
            ];
        }

        return $res;
    }
}
