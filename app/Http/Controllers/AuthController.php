<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
    *  @OA\POST(
    *      path="/api/v1/auth",
    *      summary="Realizar o login de um usuário já cadastrado",
    *      description="Login de usuário",
    *      tags={"Auth"},
    *      @OA\RequestBody(
    *          required=true,
    *          description="Dados do usuário",
    *          @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *               type="object",
    *               required={"email", "password"},
    *               @OA\Property(
    *                  property="email",
    *                  type="string",
    *                  description="Email do usuário"
    *               ),
    *               @OA\Property(
    *                  property="password",
    *                  type="string",
    *                  description="Senha do usuário"
    *               ),
    *             ),
    *          )
    *      ),
    *      @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(
    *                 property="token",
    *                 type="string",
    *                 description="Token do usuário para acesso a rotas protegidas"
    *             ),
    *             @OA\Property(
    *                 property="email",
    *                 type="string",
    *                 description="Email do usuário"
    *             ),
    *             @OA\Property(
    *                 property="id",
    *                 type="integer",
    *                 description="ID do usuário"
    *             )
    *          )
    *      ),
    *      @OA\Response(
    *          response=422,
    *          description="Unprocessable Entity",
    *          @OA\JsonContent(
    *              type="object",
    *              @OA\Property(
    *                  property="message",
    *                  type="string",
    *                  description="Detalhes do erro"
    *              ),
    *              @OA\Property(
    *                  property="errors",
    *                  type="object",
    *                  description="Objeto contendo campos específicos do formulário com mensagens de erro",
    *                  @OA\Property(
    *                      property="email",
    *                      type="array",
    *                      @OA\Items(
    *                          type="string",
    *                          description="Mensagem de erro para o campo email"
    *                      )
    *                  ),
    *                  @OA\Property(
    *                      property="password",
    *                      type="array",
    *                      @OA\Items(
    *                          type="string",
    *                          description="Mensagem de erro para o campo senha"
    *                      )
    *                  )
    *              )
    *          )
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal Server Error",
    *          @OA\JsonContent(
    *              type="object",
    *              @OA\Property(
    *                  property="message",
    *                  type="string",
    *                  description="Mensagem de erro genérica indicando falha no servidor"
    *              ),
    *              @OA\Property(
    *                  property="errors",
    *                  type="object",
    *                  @OA\Property(
    *                      property="system",
    *                      type="array",
    *                      @OA\Items(
    *                          type="string",
    *                          description="Descrição detalhada do erro"
    *                      ),
    *                      description="Erros específicos do sistema"
    *                  ),
    *                  description="Detalhes dos erros que ocorreram"
    *              )
    *          )
    *      ),
    *
    *  )
    */

    public function auth(AuthLoginRequest $request)
    {
        $credentials = $request->validated();

        try{

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

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro em nossos servidores.',
                'errors' => [
                    'system' => ['Não foi possível validar seus dados em nossos servidores, por favor entre em contato com o administrador do seu sistema.']
                ]
            ], 500);
        }

    }
}
