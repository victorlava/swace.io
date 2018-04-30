<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Cache;
use App\Jobs\UpdateAmount;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * The time in minutes on how often to execute then UpdateAmount Queue
     * Also, how long to hold the collected_amount cache in Cache storage.
     *
     * @var int
     */
    private $amountExecTime;

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->amountExecTime = 240; // 4 hours

        $schedule->job(new UpdateAmount($this->amountExecTime))
                        ->cron("*/$this->amountExecTime * * * *")
                        ->skip(function () {
                            $collected = Cache::store('file')->get('collected_amount');
                            $total = env("SALE_AMOUNT");

                            // Skip this cron if the total amount is collected
                            return ($collected >= (int)$total) ? true : false;
                        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
