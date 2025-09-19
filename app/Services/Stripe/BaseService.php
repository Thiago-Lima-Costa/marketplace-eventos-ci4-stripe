<?php

declare(strict_types=1);

namespace App\Services\Stripe;

use Stripe\Stripe;

abstract class BaseService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }
}