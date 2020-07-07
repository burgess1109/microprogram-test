<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;

class ListAction extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function __invoke()
    {
        return response()->json($this->currencyService->getAllCurrency());
    }
}
