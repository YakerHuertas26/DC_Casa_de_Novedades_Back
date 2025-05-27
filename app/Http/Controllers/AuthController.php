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

        // devuelvo 
        return response()->json([
            'token' => $token,
            'user' => [
                'id'=> $user->id,
                'name'=> $user->name,
                'userName'=> $user->userName,
                'role'=>$user->getRoleNames()->first(),
            ],
        ]);
    }

    // cerrar sesion
    public function logaut(AuthRequest $request){
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
