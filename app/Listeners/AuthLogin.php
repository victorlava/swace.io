<?php

namespace App\Listeners;

use App\Log;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\LocationMismatch;
use Mail;

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

        // 1. Get first log in ip
        // 2. Check if first log in ip is different than current ip
        // 3. If different then send mismatch message to email
        $logFirst = Log::where('user_id', $event->user->id)->first();
        if ($logFirst) { // Not first time

            if ($logFirst->ip_address !== request()->ip()) {
                $email = new LocationMismatch($logFirst->ip_address, request()->ip());
                Mail::to($event->user->email)->send($email);
            }
        }

        session()->put('log_in_date', $event->user->date_time());

        $log = new Log;
        $log->user_id = $event->user->id;
        $log->ip_address = request()->ip();
        $log->session_id = session()->getId();
        $log->user_agent = request()->header('User-Agent');
        $log->log_in = session()->get('log_in_date');
        $log->save();
    }
}
