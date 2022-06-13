<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsMenu extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'menu_id'          => [
                'type'           => 'INT',
                // 'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'menu_kode'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'menu_nama'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'menu_status'       => [
                'type'       => 'boolean',
                'default' => 1,
            ],
            'menu_url'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'menu_ikon'       => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'menu_parent_id'       => [
                'type'           => 'INT',
                'unsigned'       => true,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('menu_id', true);
        $this->forge->createTable('ms_menu');
    }

    public function down()
    {
        $this->forge->dropTable('ms_menu');
    }
}
