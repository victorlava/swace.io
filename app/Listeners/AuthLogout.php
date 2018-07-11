<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

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
     * @param  Logout $event
     * @return void
     */
    public function handle(Logout $event): void
    {
        $event->user->addLogout(session()->getId());
    }
}
