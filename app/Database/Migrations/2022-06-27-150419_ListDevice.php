<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ListDevice extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ld_id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ld_kode'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'ld_nama'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'ld_status'       => [
                'type'       => 'boolean',
                'default' => 1,
            ],
            'ld_motor'       => [
                'type'       => 'boolean',
                'default' => 0,
            ],
            'ld_kemudi'       => [
                'type'       => 'integer',
                'default' => 0,
            ],
            'ld_kompas'       => [
                'type'       => 'float',
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('ld_id', true);
        $this->forge->createTable('list_device');
    }

    public function down()
    {
        $this->forge->dropTable('list_device');
    }
}
