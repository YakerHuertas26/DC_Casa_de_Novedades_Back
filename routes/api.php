<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// usuarios autenticados
Route::middleware(['jwt.cookie','jwt.validate'])->group(function () {
    // Todas estas rutas requerirán el token JWT en cookies
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/user',[UserController::class,'user']);
});


// +++++++++++ rutas publicas ++++++++++++++ 
// login
Route::post('/login', [AuthController::class,'login'])->middleware('throttle:3,1');