<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class CoingateCallbackIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = env('COINGATE_ACCESS_ALLOW_IP');
        $ip = explode(',', $ip);

        if (!in_array($request->ip(), $ip)) {
            return response(null, Response::HTTP_UNAUTHORIZED)->json([
                'error' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized action.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
