<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MjknAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $username = $request->header('x-username');
        $password = $request->header('x-password');
        $token = $request->header('x-token');

        // Jika request ke endpoint /token, validasi username & password
        if ($request->is('api/mjkn-ws/token')) {
            if ($username === config('mjkn.ws_username') && $password === config('mjkn.ws_password')) {
                return $next($request);
            }
        } else { // Untuk endpoint lain, validasi token
            if ($token && Cache::has('mjkn_token_' . $token)) {
                return $next($request);
            }
        }

        // Jika gagal, kirim respons error
        return response()->json([
            'metadata' => [
                'message' => 'Unauthorized. Please check your credentials or token.',
                'code' => 401,
            ]
        ], 401);
    }
}
