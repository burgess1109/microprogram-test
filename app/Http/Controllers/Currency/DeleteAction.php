<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;

class DeleteAction extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function __invoke($id)
    {
        $result = $this->currencyService->deleteCurrency($id);
        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        return response()->json(['message' => 'delete successful.']);
    }
}
