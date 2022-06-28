<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataDevice extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'dd_id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ld_id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'dd_lat'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'dd_long'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('dd_id', true);
        $this->forge->addForeignKey('ld_id', 'list_device', 'ld_id', 'CASCADE', 'CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('data_device', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('data_device');
    }
}
