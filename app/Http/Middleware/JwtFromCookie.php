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
        // Verificar si existe la cookie
        if (!$request->hasCookie('auth_token')) {
            return response()->json(['message' => 'Token no proporcionado'], 401);
        }
        // Obtener el token de la cookie
        $token = $request->cookie('auth_token');
        
        try {
            // Establecer el token tanto en headers como en JWTAuth
            $request->headers->set('Authorization', 'Bearer ' . $token);
            JWTAuth::setToken($token);
            
            // Autenticar y obtener usuario
            $user = JWTAuth::authenticate();
            
            if (!$user) {
                throw new JWTException('Usuario no encontrado');
            }
            
            // Inyectar el usuario en el request para acceso fácil
            $request->merge(['user' => $user]);
            
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No autorizado',
                'error' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
