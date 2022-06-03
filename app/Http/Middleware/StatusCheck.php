<?php

namespace App\Http\Middleware;

use App\Exceptions\LoginException;
use Closure;
use Illuminate\Http\Request;

class StatusCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->status == 'blocked') {
            throw new LoginException('User is blocked');
        }

        return $next($request);
    }
}
