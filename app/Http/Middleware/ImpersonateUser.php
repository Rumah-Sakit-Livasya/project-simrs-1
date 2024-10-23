<?php
// app/Http/Middleware/ImpersonateUser .php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateUser
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan pengguna yang login adalah admin
        if (Auth::user()->hasRole('super admin')) {
            // Ambil user_id dari input
            $userId = $request->input('user_id');
            if ($userId) {
                // Login sebagai pengguna yang dipilih
                Auth::loginUsingId($userId);
            }
        }
        return $next($request);
    }
}
