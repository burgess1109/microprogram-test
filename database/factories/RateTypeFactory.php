<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RateType;

$factory->define(RateType::class, function () {
    return [
        'name' => '測試匯率'
    ];
});
