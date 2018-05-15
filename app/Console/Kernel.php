<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Cache;
use App\Jobs\UpdateAmount;
use App\Jobs\LogOutUsers;

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
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new UpdateAmount)
                        ->everyMinute()
                        ->skip(function () {
                            $collected = Cache::store('file')->get('collected_amount');
                            $total = env("SALE_AMOUNT");

                            // Skip this cron if the total amount is collected
                            return ($collected >= (int)$total) ? true : false;
                        });

        $schedule->job(new LogOutUsers)->everyMinute();
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
