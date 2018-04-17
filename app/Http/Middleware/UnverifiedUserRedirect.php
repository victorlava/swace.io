<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UnverifiedUserRedirect
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
            /* If logged in and not verified redirect to unactive dashboard index */
            return redirect()->route('dashboard.index');
        }


        return $next($request);
    }
}
