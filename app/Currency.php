<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currency';

    protected $fillable = [
        'name', 'iso_code',
    ];

    public function exchangeRate()
    {
        return $this->hasMany(ExchangeRate::class);
    }
}
