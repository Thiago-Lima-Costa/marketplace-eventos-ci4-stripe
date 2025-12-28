<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class OrderItem extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'booking_data' => '?json', //converte para objeto stdClass quando tiver valor
    ];

    public function price(): string
    {
        return show_price($this->price);
    }
}
