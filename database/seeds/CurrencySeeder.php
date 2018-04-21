<?php

use Illuminate\Database\Seeder;
use App\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
                    [
                        'title' => 'Litecoin',
                        'short_title' => "ltc"
                    ],
                    [
                        'title' => 'Bitcoin',
                        'short_title' => "btc"
                    ],
                    [
                        'title' => 'Etherium',
                        'short_title' => "eth"
                    ]
                ];

        foreach ($data as $row) {
            $currency = new Currency;
            $currency->title = $row['title'];
            $currency->short_title = $row['short_title'];
            $currency->save();
        }
    }
}
