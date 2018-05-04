<?php

namespace App\Listeners;

use App\Log;
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
        $log = new Log;
        $log->user_id = $event->user->id;
        $log->ip_address = request()->ip();
        $log->log_in = session()->get('log_in_date');
        $log->log_out = $event->user->date_time();
        $log->save();
    }
}
