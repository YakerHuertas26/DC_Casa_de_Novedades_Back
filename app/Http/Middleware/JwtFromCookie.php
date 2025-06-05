<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // quita el token de la cookie y pasa al header del HTTP
            // verifi si exite la cookie
            if ($request->hasCookie('auth_token')) {
                $request->headers->set('Authorization','bearer'.$request->cookie('auth_token'));
            }
            return $next($request);
    }
}

// 