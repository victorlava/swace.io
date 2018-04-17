<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UnverifiedUserMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->verified == 0) {
            /* If logged in and not verified show error message */
            if(!session()->get('type')) { /* Don't show the message if there are other flash messages coming in */
                session()->flash('type', 'error');
                session()->flash('message', 'Your email ' . Auth::user()->email . ' is not verified, you can not use the dashboard.');
                }

            }

        return $next($request);
    }
}
