<?php

namespace App\Http\Middleware;

use Closure;
use App\Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        if (!Auth::user()->isVerified()) {
            /* If logged in and not verified show error message */
            if (!session()->get('type')) { /* Don't show the message if there are other flash messages coming in */
                Flash::create(
                    'error',
                    'Your email ' . Auth::user()->email . ' is not verified, you can not use the dashboard.'
                );
            }
        }

        return $next($request);
    }
}
