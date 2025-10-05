<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusSeatBooking: string
{
    Case Reserved = 'reserved';
    Case Sold = 'sold';
    Case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Reserved => 'Reservado',
            self::Sold => 'Vendido',
            self::Pending => 'Aguardando pagamento',
        };
    }
}