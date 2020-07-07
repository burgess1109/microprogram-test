<?php

namespace App\Services;

use App\Currency;
use App\ExchangeRate;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CurrencyService
{
    private $cacheTTL = 3600;

    public function getAllCurrency()
    {
        if (Redis::get('currency:all')) {
            return json_decode(Redis::get('currency:all'));
        }

        $response = [];
        $currency = Currency::select(['id', 'name', 'iso_code'])
            ->with('exchangeRate:currency_id,rate_type_id,buying_rate,selling_rate')
            ->with('exchangeRate.rateType:id,name')
            ->get();

        foreach ($currency as $key => $c) {
            $response[$key] = $this->assembleCurrencyResponse($c);
        }

        if (!empty($response)) {
            Redis::setex('currency:all', $this->cacheTTL, json_encode($response));
        }

        return $response;
    }

    public function getCurrency($id)
    {
        if (Redis::get('currency:' . $id)) {
            return json_decode(Redis::get('currency:' . $id));
        }

        $currency = Currency::select(['id', 'name', 'iso_code'])
            ->with('exchangeRate:currency_id,rate_type_id,buying_rate,selling_rate')
            ->with('exchangeRate.rateType:id,name')
            ->where('id', $id)
            ->first();
        $currencyResponse = $this->assembleCurrencyResponse($currency);

        if (!empty($currencyResponse)) {
            Redis::setex('currency:' . $id, $this->cacheTTL, json_encode($currencyResponse));
        }

        return $currencyResponse;
    }

    private function assembleCurrencyResponse($currency)
    {
        $response = null;

        if (!empty($currency)) {
            $response = [
                'id' => $currency->id,
                'name' => $currency->name,
                'iso_code' => $currency->iso_code
            ];

            foreach ($currency->exchangeRate as $rate) {
                $response['exchange_rate'][] = [
                    'name' => $rate->rateType->name,
                    'selling_rate' => $rate->selling_rate,
                    'buying_rate' => $rate->buying_rate,
                ];
            }
        }

        return $response;
    }

    public function createCurrencyAndExchangeRate($name, $isoCode, $exchangeRate)
    {
        try {
            DB::beginTransaction();
            $currency = Currency::create([
                'name' => $name,
                'iso_code' => $isoCode,
            ]);

            if ($exchangeRate) {
                $currency->exchangeRate()->createMany((array)$exchangeRate);
            }
            DB::commit();
            return $currency;
        } catch (Exception $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function updateCurrencyAndExchangeRate($id, $updateData)
    {
        try {
            DB::beginTransaction();
            Currency::findOrFail($id)->update($updateData);

            if (isset($updateData['exchange_rate'])) {
                foreach ($updateData['exchange_rate'] as $rate) {
                    ExchangeRate::where('currency_id', $id)
                        ->where('rate_type_id', $rate['rate_type_id'])
                        ->firstOrFail()
                        ->update($rate);
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteCurrency($id)
    {
        try {
            DB::beginTransaction();
            $currency = Currency::findOrFail($id);
            $currency->exchangeRate()->delete();
            $currency->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }
}
