<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\Order;

class UpdateAmount implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Collected Amount
    |--------------------------------------------------------------------------
    |
    | Queries through paid order and sums up the total amount, saves that
    | into the cache storage. If SALE_AMOUNT is reached from .env then doesn't
    | fire anymore.
    |
    */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $amount = Order::whereHas('status', function ($query) {
            $query->where('title', 'Paid');
        })->sum('tokens');

        if (!$amount) {
            $amount = 0;
        }

        Storage::disk('local')->put('collected_amount.txt', $amount, 720);
    }
}
