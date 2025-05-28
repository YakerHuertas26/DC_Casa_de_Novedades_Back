<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    
    public function login (AuthRequest $request){

        $credentials = $request->validated();

        // si no los datos no existen y ademas solo para usaurios activos
        if (!Auth::attempt(array_merge($credentials, ['state' => 1]))) {
            // notifico el error 
            throw ValidationException::withMessages([
                'message' => 'credenciales invalidas',
            ]);
        }

        // obtengo el user autentificado 
        $user = Auth::user();
        // creo el token
        $token = $user->createToken('auth_token')->plainTextToken;

        // cookie de sesion con HttpOnly
        $cookie= cookie(
            'auth_token', // Nombre de la cookie
            $token,       // Valor (token)
            60 * 24 * 7,   // Expira en 7 días (en minutos) // null = hasta cerrar sesión)
            '/',          // Ruta válida (todo el dominio)
            null,         // Dominio (null = dominio actual)
            false,        // Solo HTTPS (cambia a `true` en producción)
            true,         // httpOnly (seguridad contra XSS)
            false,        // sameSite (puede ser 'lax' o 'strict' en producción)
        );

        // devuelvo incluyendo mi coockie
        return response()->json([
            'user' => [
                'id'=> $user->id,
                'name'=> $user->name,
                'userName'=> $user->userName,
                'role'=>$user->getRoleNames()->first(),
            ],
        ])->withoutCookie($cookie);
    }

    // cerrar sesion
    public function logaut(AuthRequest $request){
        // Cerrar sesión solo en este dispositivo
        $request->user()->currentAccessToken()->delete();

        // Cerrar sesión en todos los dispositivos
        // $request->user()->tokens()->delete();

        
        // Invalida la cookie
        $cookie = cookie()->forget('auth_token');
        
        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ])->withCookie($cookie);
    }
}
