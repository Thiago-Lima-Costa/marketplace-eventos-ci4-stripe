<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEvents extends Migration
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

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ],

            'code' => [
                'type' => 'INT',
                'constraint' => 8,
                'null' => false,
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],

            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],

            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],

            'start_date' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'Data e hora da primeira apresentação',
            ],

            'end_date' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => 'Data e hora da última apresentação',
            ],

            'description' => [
                'type' => 'TEXT',
                'constraint' => 2000,
                'null' => false,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('code');
        $this->forge->addKey('name');
        $this->forge->addKey('start_date');
        $this->forge->addKey('end_date');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('events');
    }

    public function down()
    {
        $this->forge->dropTable('events');
    }
}
