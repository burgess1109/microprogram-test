<?php

namespace Tests\Feature;

use App\Currency;
use App\ExchangeRate;
use App\RateType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function testCurrency()
    {
        factory(ExchangeRate::class)->create();

        $response = $this->get('/currency');

        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'iso_code',
                'exchange_rate' => [
                    '*' => [
                        'name',
                        'selling_rate',
                        'buying_rate'
                    ]
                ]
            ]
        ]);
    }

    public function testSingleCurrency()
    {
        $exchangeRate = factory(ExchangeRate::class)->create();

        $response = $this->get('/currency/' . $exchangeRate->currency_id);
        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'name',
            'iso_code',
            'exchange_rate' => [
                '*' => [
                    'name',
                    'selling_rate',
                    'buying_rate'
                ]
            ]
        ]);
    }

    public function testCreateCurrency()
    {
        $currency = factory(Currency::class)->make();
        $rateType = factory(RateType::class)->create();

        $createData = [
            'name' => $currency->name,
            'iso_code' => $currency->iso_code,
            'exchange_rate' => [
                [
                    'rate_type_id' => $rateType->id,
                    'selling_rate' => 1.11,
                    'buying_rate' => 1.11,
                ]
            ]
        ];

        $response = $this->post('/currency', $createData);
        $response->assertStatus(200)->assertJson([
            'name' => $currency->name,
            'iso_code' => $currency->iso_code,
        ])->assertJsonStructure([
            'id',
            'name',
            'iso_code',
            'updated_at',
            'created_at'
        ]);
    }

    public function testUpdateAction()
    {
        $exchangeRate = factory(ExchangeRate::class)->create();

        $updateData = [
            'name' => '測試幣II',
            'iso_code' => 'T2',
            'exchange_rate' => [
                [
                    'rate_type_id' => $exchangeRate->rate_type_id,
                    'selling_rate' => 2.22,
                    'buying_rate' => 2.22,
                ]
            ]
        ];

        $response = $this->patch('/currency/' . $exchangeRate->currency_id, $updateData);
        $response->assertStatus(200)->assertJson([
            'message' => 'update successful.'
        ]);
    }

    public function testDeleteAction()
    {
        $exchangeRate = factory(ExchangeRate::class)->create();
        $this->delete('/currency/' . $exchangeRate->currency_id);
        $this->assertDeleted($exchangeRate);
    }
}
