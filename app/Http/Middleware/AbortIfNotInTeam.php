<?php

namespace App\Http\Middleware;

use Closure;

class AbortIfNotInTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $team = null)
    {
        if (!$request->user()->teams->contains($team)) {
            return abort(403);
        }

        return $next($request);
    }
}
