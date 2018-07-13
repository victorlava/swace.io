<?php

namespace App\Jobs;

use App\Session;
use Carbon\Carbon;
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
        $sessions = Session::with('user')->get();
        $hour = 3600;

        foreach ($sessions as $session) {
            $timePassed = Carbon::now()->diffInSeconds($session->last_activity, true);

            if ($timePassed >= $hour && $user = $session->user()->first()) {
                $user->addLogout();
                $session->delete();
            }
        }
    }
}
