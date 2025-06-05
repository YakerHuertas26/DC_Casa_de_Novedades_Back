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

        // credenciales invalidas y ademas solo para usaurios activos
        // token explicito
        if (!$token= Auth::guard('api')->attempt(array_merge($credentials, ['state' => 1]))) {
            // notifico el error 
            return response()->json(['message'=>'credenciales invalidas'],401);
        }

        // obtengo los datos del usuario   
        $user = Auth::guard('api')->user();

        // guardo el token en la cookie HttpOnly
        $cookie= cookie(
            'auth_token', // Nombre de la cookiee
            $token,       // Valor (token)
            config('jwt.ttl'),   // Expira en 1 días // null = hasta cerrar sesión)
            '/',          // Ruta (por defecto toda la app)
            null,         // Dominio (null dominio actual) en producción cambia
            config('app.env') === 'production', // Secure: Solo HTTPS (cambia a `true` en producción)
            true,         // httpOnly (seguridad contra XSS) no accesible desde JavaScript
            false,        //cookie no será codificada defaul no mover
            false,        // sameSite (puede ser 'lax' o 'strict' en producción)
        );

        // devuelvo incluyendo mi coockie
        return response()->json([
            'user'=> [
                    'name'=>$user->name,
                    'userName'=>$user->userName,
                    'role'=> $user->getRoleNames()->first(),
                ]
        ])->withCookie($cookie);
    }

    // cerrar sesion
    public function logout (Request $request){
        try {
            Auth::guard('api')->logout();

            return response()->json(['message' => 'Sessión cerrada'])
        ->cookie('auth_token', '', -1); // Elimina cookie
        } 
        catch (\Throwable $th) {
            return response()->json(['messaje'=>"Error al cerrar sesión"],500);
        }
    }
}
