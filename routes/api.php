<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1'], function () {
    Route::controller( UserController::class)->group(function () {
        Route::post('/user', 'store');
        Route::delete('/user/{user}', 'destroy')->missing(function (Request $request) {
            return response()->json([
                'message' => 'Usuário não encontrado.',
                'errors' => [
                    'user' => ['Erro ao recuperar as informações do usuário.']
                ]
            ], 404);
        });
        Route::get('/user/{user}', 'show')->missing(function (Request $request) {
            return response()->json([
                'message' => 'Usuário não encontrado.',
                'errors' => [
                    'user' => ['Erro ao recuperar as informações do usuário.']
                ]
            ], 404);
        });
    });

    Route::controller( AuthController::class)->group(function () {
        Route::post('/auth', 'auth');
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
