<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// usuarios autenticados
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// +++++++++++ rutas publicas ++++++++++++++ 
// login
Route::post('/login', [AuthController::class,'login'])->middleware('throttle:3,0.5');