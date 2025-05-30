<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    
    public function login (AuthRequest $request){
        $credentials = $request->validated();

        // si no los datos no existen y ademas solo para usaurios activos
        if (!$token= Auth::guard('api')->attempt(array_merge($credentials, ['state' => 1]))) {
            // notifico el error 
            throw ValidationException::withMessages([
                'message' => 'credenciales invalidas',
            ]);
        }

        // obtengo el user autentificado 
        $user = Auth::guard('api')->user();
        // guardo el token en la cookie

        // // cookie de sesion con HttpOnly
        $cookie= cookie(
            'auth_token', // Nombre de la cookie
            $token,       // Valor (token)
            60 * 24 * 1,   // Expira en 7 días (en minutos) // null = hasta cerrar sesión)
            '/',          // Ruta (por defecto toda la app)
            null,         // Dominio (null dominio actual) en producción cambia
            false,        // Secure: Solo HTTPS (cambia a `true` en producción)
            true,         // httpOnly (seguridad contra XSS) no accesible desde JavaScript
            false,        // sameSite (puede ser 'lax' o 'strict' en producción)
        );

        // devuelvo incluyendo mi coockie
        return response()->json([
            'user'=> new UserResource($user)
        ])->withCookie($cookie);
    }

    // cerrar sesion
    public function logout (Request $request){
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Logged out'])
        ->cookie('auth_token', '', -1); // Elimina cookie
    }
}
