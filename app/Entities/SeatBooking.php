<?php

namespace App\Entities;

use App\Enums\StatusSeatBooking;
use CodeIgniter\Entity\Entity;

class SeatBooking extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [];

    public function type(): string
    {
        return $this->type === 'full' ? 'Inteira' : 'Meia';
    }

    public function number(): string
    {
        return "<span class='badge badge-primary'>{$this->number}</span>";
    }

    public function status(): string
    {
        return StatusSeatBooking::tryFrom($this->status)?->label() ?? $this->status;
    }

    public function isReserved(): bool
    {
        return $this->status === StatusSeatBooking::Reserved->value;
    }

    public function isSold(): bool
    {
        return $this->status === StatusSeatBooking::Sold->value;
    }

    public function isPending(): bool
    {
        return $this->status === StatusSeatBooking::Pending->value;
    }
}
