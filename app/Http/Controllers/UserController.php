<?php

namespace App\Http\Controllers;

;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $user = Auth::guard('api')->user();
    
    if (!$user) {
        return response()->json(['message' => 'Usuario no autenticado'], 401);
    }
    
    return new UserResource($user);
    }
}
