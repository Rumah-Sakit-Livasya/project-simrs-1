<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class CheckApiCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = "fizar*#";
        $password = "#*ganteng";

        $headerUsername = $request->header('Username');
        $headerPassword = $request->header('Password');


        if ($username !== $headerUsername || $password !== $headerPassword) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
