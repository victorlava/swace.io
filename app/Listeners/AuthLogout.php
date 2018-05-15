<?php

namespace App\Listeners;

use App\Log;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $log = Log::where('user_id', $event->user->id)->where('session_id', session()->getId())->first();
        if ($log) {
            $log->log_out = $event->user->date_time();
            $log->save();
        }
    }
}
