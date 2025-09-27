<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectors extends Migration
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

            'event_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'rows_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => 'Número de filas',
            ],

            'seats_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => 'Número de assentos por filas',
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => false,
            ],

            'ticket_price' => [
                'type' => 'VARCHAR',
                'constraint' => 11,
                'comment' => 'Preço da entrada integral em centavos',
            ],

            'discounted_price' => [
                'type' => 'VARCHAR',
                'constraint' => 11,
                'comment' => 'Preço da meia-entrada entrada em centavos',
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_id');
        $this->forge->addKey('name');
        $this->forge->addKey('ticket_price');
        $this->forge->addKey('discounted_price');

        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('sectors', attributes: ['comment' => 'Tabela de setores dos eventos']);
    }

    public function down()
    {
        $this->forge->dropTable('sectors');
    }
}
