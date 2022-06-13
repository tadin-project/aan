<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataRecord extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'dr_id'     => [
                'type'              => 'INT',
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'record_at' => [
                'type'              => 'datetime',
                'null'              => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('dr_id', true);
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('data_record', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('data_record');
    }
}
