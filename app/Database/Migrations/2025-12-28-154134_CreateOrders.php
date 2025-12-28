<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrders extends Migration
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
            ],

            'event_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'code' => [
                'type' => 'INT',
                'constraint' => 8,
                'comment' => 'Código interno do pedido',
            ],

            'stripe_session_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],

            'total' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Preço total em centavos',
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
        $this->forge->addKey('stripe_session_id', unique: true);
        $this->forge->addKey('code', unique: true);
        $this->forge->addKey('status');
        $this->forge->addKey('total');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('orders', attributes: ['comment' => 'Tabela de pedidos']);


        // -------------- itens do pedido -------------- //

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'booking_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Referência à reserva do assento',
            ],

            'price' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Preço total em centavos no momento da compra',
            ],

            'booking_data' => [
                'type' => 'JSON',
                'comment' => 'Dados do assento, com setor, fila, etc, para identificação do item',
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
        $this->forge->addKey('order_id');
        $this->forge->addKey('booking_id');
        $this->forge->addKey('price');

        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booking_id', 'seat_bookings', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('order_items', attributes: ['comment' => 'Tabela de itens de cada pedido']);

    }

    public function down()
    {
        $this->forge->dropTable('order_items');
        $this->forge->dropTable('orders');
    }
}
