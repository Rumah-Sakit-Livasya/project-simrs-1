<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LastSeenUser
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_seen = Carbon::now();
            $user->save();
        }

        return $next($request);
    }
}
