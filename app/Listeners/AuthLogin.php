<?php

namespace App\Listeners;

use App\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthLogin
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
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
        $log->session_token = csrf_token();
        $log->user_id = $event->user->id;
        $log->ip_address = request()->ip();
        $log->log_in = $event->user->date_time();
        $log->save();
    }
}
