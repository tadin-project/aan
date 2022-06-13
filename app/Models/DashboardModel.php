<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    // protected $DBGroup          = 'default';
    // protected $table            = 'ms_user';
    // protected $primaryKey       = 'user_id';
    // protected $useAutoIncrement = true;
    // protected $insertID         = 0;
    // protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    // protected $allowedFields    = [
    //     "user_id",
    //     "user_name",
    //     "user_status",
    //     "is_superuser",
    //     "password",
    //     "user_fullname",
    //     "user_email",
    // ];

    // // Dates
    // protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];

    public function get_total($where)
    {
        $sql = "SELECT
                    count(*) as total
                from
                    data_device dd
                where
                    0 = 0
                    $where";
        return $this->db->query($sql)->getRow()->total;
    }

    public function get_data($limit, $where, $order)
    {
        $sql = "SELECT
                    *
                from
                    data_device dd
                where
                    0 = 0
                    $where
                $order $limit";
        return $this->db->query($sql)->getResult();
    }
}
