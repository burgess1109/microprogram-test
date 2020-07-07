<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Currency;

$factory->define(Currency::class, function () {
    return [
        'id' => 1,
        'name' => '測試幣',
        'iso_code' => 'TEST'
    ];
});
