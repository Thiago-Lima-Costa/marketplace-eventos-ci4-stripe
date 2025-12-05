<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class CartSeatsCell extends Cell
{
    protected array $seats = [];
    protected bool $showDeleteButton = false;

    public function getSeatsProperty(): array
    {
        return $this->seats;
    }

    public function getShowDeleteButtonProperty(): bool
    {
        return $this->showDeleteButton;
    }
}
