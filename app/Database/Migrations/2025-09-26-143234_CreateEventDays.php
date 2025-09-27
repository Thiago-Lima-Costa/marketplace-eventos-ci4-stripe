<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventDays extends Migration
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

            'event_date' => [
                'type' => 'DATETIME',
                'comment' => 'Data e hora da apresentação do evento',
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_id');
        $this->forge->addKey('event_date');

        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('event_days', attributes: ['comment' => 'Tabela de datas do evento']);
    }

    public function down()
    {
        $this->forge->dropTable('event_days');
    }
}
