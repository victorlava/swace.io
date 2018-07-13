<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Session;

class LogOutUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sessions = Session::all();
        $currentTimestamp = time();
        $hour = 3600;

        foreach ($sessions as $session) {
            $timePassed = $currentTimestamp - $session->last_activity;

            if($session->user()) {
              if ($timePassed >= $hour) {
                    $session->user()->addLogout();
                    $session->delete();
              }
            }
        }
    }
}
