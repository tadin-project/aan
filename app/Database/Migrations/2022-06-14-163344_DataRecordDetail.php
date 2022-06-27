<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataRecordDetail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'drd_id'     => [
                'type'              => 'INT',
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'dr_id'     => [
                'type'              => 'INT',
                'unsigned'          => true,
            ],
            'drd_lat'   => [
                'type'              => 'text',
            ],
            'drd_long' => [
                'type'              => 'text',
            ],
            'drd_accuracy' => [
                'type'              => 'text',
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('dr_id', true);
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('data_record', false, $attributes);
    }

    public function down()
    {
        //
    }
}
