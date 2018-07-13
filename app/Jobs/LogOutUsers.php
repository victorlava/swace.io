<?php

namespace App\Jobs;

use App\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogOutUsers implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

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

    public function handle(): void
    {
        /** @var Session[] $sessions */
        $sessions = Session::with('user')->all();
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
