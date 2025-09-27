<?php

if(! function_exists('remove_non_numeric')) {

    function remove_non_numeric(string $value): string
    {
        return preg_replace('/\D/', '', $value);
    }

}

if(! function_exists('show_price')) {

    function show_price(int|float $price): string
    {
        return number_to_currency(num: $price / 100, currency: 'BRL', fraction: 2);
    }

}