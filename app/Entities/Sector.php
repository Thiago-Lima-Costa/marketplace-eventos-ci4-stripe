<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Sector extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [];

    public function setTicketPrice(string|int $ticketPrice): self
    {
        $ticketPrice = remove_non_numeric($ticketPrice);
        $this->attributes['ticket_price'] = intval($ticketPrice);
        return $this;
    }

    public function setDiscountedPrice(string|int $discountedPrice): self
    {
        $discountedPrice = remove_non_numeric($discountedPrice);
        $this->attributes['discounted_price'] = intval($discountedPrice);
        return $this;
    }

    public function ticketPrice(): string
    {
        return show_price($this->ticket_price);
    }

    public function discountedPrice(): string
    {
        return show_price($this->discounted_price);
    }

    public function totalSeats(): string
    {
        return $this->rows_count * $this->seats_count;
    }
}
