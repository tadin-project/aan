<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PasswordReset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pr_id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pr_token'          => [
                'type'           => 'varchar',
                'constraint'    => '255',
            ],
            'pr_expire'          => [
                'type'           => 'timestamp',
            ],
            'user_id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addKey('pr_id', true);
        $this->forge->addForeignKey('user_id', 'ms_user', 'user_id', 'CASCADE', 'CASCADE');
        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->createTable('password_reset', false, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('password_reset');
    }
}
