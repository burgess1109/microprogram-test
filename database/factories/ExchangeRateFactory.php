<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Currency;
use App\ExchangeRate;
use App\RateType;

$factory->define(ExchangeRate::class, function () {
    return [
        'currency_id' => factory(Currency::class),
        'rate_type_id' => factory(RateType::class),
        'buying_rate' => 1.11,
        'selling_rate' => 1.11,
    ];
});
