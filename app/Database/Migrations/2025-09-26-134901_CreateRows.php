<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRows extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'sector_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => false,
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('sector_id');
        $this->forge->addKey('name');

        $this->forge->addForeignKey('sector_id', 'sectors', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('rows', attributes: ['comment' => 'Tabela de fileiras de um setor']);
    }

    public function down()
    {
        $this->forge->dropTable('rows');
    }
}
