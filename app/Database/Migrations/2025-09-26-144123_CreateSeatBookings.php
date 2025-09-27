<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeatBookings extends Migration
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

            'seat_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'event_day_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'payment_intent' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
                'comment' => 'Receberá o ID da intenção de pagamento junto à stripe',
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['reserved', 'pending', 'sold'],
                'default' => 'reserved',
                'comment' => 'Status da reserva desse assento',
            ],

            'type' => [
                'type' => 'ENUM',
                'constraint' => ['full', 'half'],
                'default' => 'full',
                'comment' => 'Tipo de entrada (inteira ou meia-entrada)',
            ],

            'price' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Preço do ingresso no momento da reserva em centavos',
            ],

            'expire_at' => [
                'type' => 'DATETIME',
                'comment' => 'Horário da expiração da reserva quando for o caso',
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],

        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_day_id');
        $this->forge->addKey('seat_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('payment_intent');
        $this->forge->addKey('status');
        $this->forge->addKey('type');
        $this->forge->addKey('price');
        $this->forge->addKey('expire_at');

        $this->forge->addForeignKey('event_day_id', 'event_days', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seat_id', 'seats', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('seat_bookings', attributes: ['comment' => 'Tabela de reservas dos assentos']);
    }

    public function down()
    {
        $this->forge->dropTable('seat_bookings');
    }
}
