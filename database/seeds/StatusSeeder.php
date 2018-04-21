<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusSeeder extends Seeder
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
                        'status' => 'Invalid',
                        'description' => "when CoinGate fails to create an order, this one shouldn't occur normally"
                    ],
                    [
                        'status' => 'Pending',
                        'description' => "order processed by CoinGate"
                    ],
                    [
                        'status' => 'Expired',
                        'description' => "order marked as expired by Coingate"
                    ],
                    [
                        'status' => 'Paid',
                        'description' => "order marked as paid by Coingate"
                    ],
                    [
                        'status' => 'Canceled',
                        'description' => "user canceled order from Coingate"
                    ]
                ];

        foreach ($data as $row) {
            $status = new Status;
            $status->title = $row['status'];
            $status->description = $row['description'];
            $status->save();
        }

    }
}
