<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeats extends Migration
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

            'row_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'number' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Número do assento na fileira',
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('row_id');
        $this->forge->addKey('number');

        $this->forge->addForeignKey('row_id', 'rows', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('seats', attributes: ['comment' => 'Tabela de assentos das fileiras']);
    }

    public function down()
    {
        $this->forge->dropTable('seats');
    }
}
