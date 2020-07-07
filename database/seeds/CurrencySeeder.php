<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyData = [
            [
                'name' => '美金',
                'isoCode' => 'USD',
                'bankNoteRate' => [
                    'buyingRate' => 30.345,
                    'sellingRate' => 31.115,
                ],
                'spotRate' => [
                    'buyingRate' => 30.795,
                    'sellingRate' => 30.895,
                ]
            ],
            [
                'name' => '港幣',
                'isoCode' => 'HKD',
                'bankNoteRate' => [
                    'buyingRate' => 3.765,
                    'sellingRate' => 3.981,
                ],
                'spotRate' => [
                    'buyingRate' => 3.901,
                    'sellingRate' => 3.961,
                ]
            ],
            [
                'name' => '英鎊',
                'isoCode' => 'GBP',
                'bankNoteRate' => [
                    'buyingRate' => 38.86,
                    'sellingRate' => 40.98,
                ],
                'spotRate' => [
                    'buyingRate' => 39.86,
                    'sellingRate' => 40.28,
                ]
            ],
            [
                'name' => '澳幣',
                'isoCode' => 'AUD',
                'bankNoteRate' => [
                    'buyingRate' => 21.59,
                    'sellingRate' => 22.37,
                ],
                'spotRate' => [
                    'buyingRate' => 21.86,
                    'sellingRate' => 22.09,
                ]
            ],
            [
                'name' => '加拿大幣',
                'isoCode' => 'CAD',
                'bankNoteRate' => [
                    'buyingRate' => 22.59,
                    'sellingRate' => 23.5,
                ],
                'spotRate' => [
                    'buyingRate' => 22.98,
                    'sellingRate' => 23.2,
                ]
            ],
            [
                'name' => '新加坡幣',
                'isoCode' => 'SGD',
                'bankNoteRate' => [
                    'buyingRate' => 22.18,
                    'sellingRate' => 23.09,
                ],
                'spotRate' => [
                    'buyingRate' => 22.67,
                    'sellingRate' => 22.85,
                ]
            ],
            [
                'name' => '瑞士法朗',
                'isoCode' => 'CHF',
                'bankNoteRate' => [
                    'buyingRate' => 29.54,
                    'sellingRate' => 30.74,
                ],
                'spotRate' => [
                    'buyingRate' => 30.2,
                    'sellingRate' => 30.49,
                ]
            ],
            [
                'name' => '日圓',
                'isoCode' => 'JPY',
                'bankNoteRate' => [
                    'buyingRate' => 0.2665,
                    'sellingRate' => 0.2793,
                ],
                'spotRate' => [
                    'buyingRate' => 0.2738,
                    'sellingRate' => 0.2778,
                ]
            ],
        ];

        $bankNoteRateId = DB::table('rate_type')->insertGetId([
            'name' => '現金匯率'
        ]);

        $spotRateId = DB::table('rate_type')->insertGetId([
            'name' => '即期匯率'
        ]);

        foreach ($currencyData as $currency) {
            $currencyId = DB::table('currency')->insertGetId(
                [
                    'name' => $currency['name'],
                    'iso_code' => $currency['isoCode'],
                ]
            );

            DB::table('exchange_rate')->insert([
                [
                    'currency_id' => $currencyId,
                    'rate_type_id' => $bankNoteRateId,
                    'buying_rate' => $currency['bankNoteRate']['buyingRate'],
                    'selling_rate' => $currency['bankNoteRate']['sellingRate'],
                ],
                [
                    'currency_id' => $currencyId,
                    'rate_type_id' => $spotRateId,
                    'buying_rate' => $currency['spotRate']['buyingRate'],
                    'selling_rate' => $currency['spotRate']['sellingRate'],
                ],
            ]);
        }
    }
}
