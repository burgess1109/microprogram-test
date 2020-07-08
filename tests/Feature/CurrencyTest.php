<?php

namespace Tests\Feature;

use App\Currency;
use App\ExchangeRate;
use App\RateType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

    /**
     * @dataProvider emptyRequiredInputProvider
     * @dataProvider invalidInputProvider
     * @param $invalidInput
     */
    public function testStoreActionValidation($invalidInput)
    {
        $response = $this->post('/currency', $invalidInput);
        $response->assertStatus(400)->assertJsonStructure(['error']);
    }

    /**
     * @dataProvider invalidInputProvider
     * @param $invalidInput
     */
    public function testUpdateActionValidation($invalidInput)
    {
        $response = $this->patch('/currency/1', $invalidInput);
        $response->assertStatus(400)->assertJsonStructure(['error']);
    }

    public function emptyRequiredInputProvider()
    {
        return [
            'empty input' => [['']],
            'no required field' => [['xxx' => 'test', 'names' => 'test', 'isoCode' => 'TEST']],
            'no iso_code' => [['name' => 'test']],
            'no name' => [['iso_code' => 'TEST']],
        ];
    }

    public function invalidInputProvider()
    {
        return [
            'length of iso_code is over 5' => [['name' => 'test', 'iso_code' => Str::random(6)]],
            'length of name is over 5' => [['name' => Str::random(21), 'iso_code' => 'TEST']],
            'no rate_type_id if has selling_rate' => [
                [
                    'name' => 'test',
                    'iso_code' => 'TEST',
                    'exchange_rate' => [
                        [
                            'selling_rate' => 1.11
                        ]

                    ]
                ]
            ],
            'no rate_type_id if has buying_rate' => [
                [
                    'name' => 'test',
                    'iso_code' => 'TEST',
                    'exchange_rate' => [
                        [
                            'buying_rate' => 1.11
                        ]

                    ]
                ]
            ],
            'no rate_type_id if has selling_rate & buying_rate' => [
                [
                    'name' => 'test',
                    'iso_code' => 'TEST',
                    'exchange_rate' => [
                        [
                            'selling_rate' => 1.11,
                            'buying_rate' => 1.11
                        ]

                    ]
                ]
            ],
            'rate_type_id is string not integer' => [
                [
                    'name' => 'test',
                    'iso_code' => 'TEST',
                    'exchange_rate' => [
                        [
                            'rate_type_id' => 'abc',
                            'selling_rate' => 1.11,
                            'buying_rate' => 1.11
                        ]
                    ]
                ]
            ],
            'rate_type_id is float not integer' => [
                [
                    'name' => 'test',
                    'iso_code' => 'TEST',
                    'exchange_rate' => [
                        [
                            'rate_type_id' => 1.23,
                            'selling_rate' => 1.11,
                            'buying_rate' => 1.11
                        ]
                    ]
                ]
            ],
            'selling_rate is not number' => [
                [
                    'name' => 'test',
                    'iso_code' => 'TEST',
                    'exchange_rate' => [
                        [
                            'rate_type_id' => 1,
                            'selling_rate' => 'abc',
                            'buying_rate' => 1.11
                        ]
                    ]
                ]
            ],
            'buying_rate is not number' => [
                [
                    'name' => 'test',
                    'iso_code' => 'TEST',
                    'exchange_rate' => [
                        [
                            'rate_type_id' => 1,
                            'selling_rate' => 1.11,
                            'buying_rate' => 'abc'
                        ]
                    ]
                ]
            ],
        ];
    }
}
