<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreAction extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'iso_code' => 'required|string|max:5',
            'exchange_rate.*.rate_type_id' => 'required_with:exchange_rate.*.selling_rate,exchange_rate.*.buying_rate|integer',
            'exchange_rate.*.selling_rate' => 'numeric',
            'exchange_rate.*.buying_rate' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $result = $this->currencyService->createCurrencyAndExchangeRate(
            $request->input('name'),
            $request->input('iso_code'),
            $request->input('exchange_rate', null)
        );

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        return response()->json($result);
    }
}
