<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ValidateJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // validar el JWT token
        try {
            JWTAuth::parseToken()->authenticate();
        } 
        // si el token ha expirado 
        catch (TokenExpiredException $e) {
            return response()->json(['message'=>'Tu sesión ha expirado'],401)
            ->cookie('auth_token', '', -1);
        }
        // token invalido u otros errores
        catch (JWTException $e){
            return response()->json(['message'=>'Token invalido'],401);
        }
        
        return $next($request);
    }

    public function forceLogout ($message){
        return response()->json([
            'message'=>$message,
            'logout'=>true
        ],401)
        ->cookie('auth_token', '', -1);
    }
}
