<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'exchange_rate';

    protected $fillable = [
        'currency_id', 'rate_type_id', 'buying_rate', 'selling_rate',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function rateType()
    {
        return $this->belongsTo(RateType::class);
    }
}
