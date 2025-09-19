<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'stripe_account_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
                'comment' => 'Identificador da conta Stripe para receber os repasses, quando for criador de eventos'
            ],

            'stripe_account_is_completed' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => false,
                'comment' => 'Indica se a conta Stripe Conta Conecta está com todos os dados verificados'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['stripe_account_id', 'stripe_account_is_completed']);
    }
}
