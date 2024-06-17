<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function auth(AuthLoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Erro ao efetuar o login, verifique suas credenciais.',
                'errors' => [
                    'user' => ['Erro ao recuperar o usuário.']
                ]
            ], 401);
        }

        $abilities = ['*'];

        $token = $request->user()->createToken('authToken', $abilities, now()->addDay())->plainTextToken;

        return response()->json([
            'token' => $token,
            'id' => $request->user()->id,
            'email' => $request->user()->email,
        ]);
    }
}
