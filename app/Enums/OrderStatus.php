<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    Case UnPaid = 'unpaid';
    Case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::UnPaid => 'Não pago',
            self::Paid => 'Pago',
        };
    }
}