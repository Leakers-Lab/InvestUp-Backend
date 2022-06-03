<?php

namespace App\Http\Middleware;

use App\Exceptions\NotBelongsException;
use Closure;
use Illuminate\Http\Request;

class BelongingCheck
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
        if ($request->company_id || $request->project_id) {
            $is_true = $request->user()->Companies->Projects->where('id', $request->project_id)->first();
            $is_true1 = $request->user()->Companies->where('id', $request->company_id)->first();

            if (!$is_true || !$is_true1) throw new NotBelongsException("Not Belongs to current User");;

        }

        return $next($request);
    }
}
