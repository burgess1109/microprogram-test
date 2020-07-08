<?php

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateAction extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function __invoke(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:20',
            'iso_code' => 'string|max:5|',
            'exchange_rate.*.rate_type_id' =>
                'required_with:exchange_rate.*.selling_rate,exchange_rate.*.buying_rate|integer',
            'exchange_rate.*.selling_rate' => 'numeric',
            'exchange_rate.*.buying_rate' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $result = $this->currencyService->updateCurrencyAndExchangeRate($id, (array)$request->input());

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        return response()->json(['message' => 'update successful.']);
    }
}
