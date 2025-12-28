<?php

namespace App\Entities;

use App\Enums\OrderStatus;
use CodeIgniter\Entity\Entity;

class Order extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [];

    public function total(): string
    {
        return show_price($this->total);
    }

    public function status(): string
    {
        return OrderStatus::tryFrom($this->status)?->label() ?? $this->status();
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid->value;
    }
}
