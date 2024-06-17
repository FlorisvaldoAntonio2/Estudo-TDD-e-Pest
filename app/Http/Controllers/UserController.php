<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
    *  @OA\POST(
    *      path="/api/v1/user",
    *      summary="Adiciona um novo usuário",
    *      description="Cadastro de usuário",
    *      tags={"User"},
    *       @OA\RequestBody(
    *    required=true,
    *    description="Dados do novo usuário",
    *    @OA\MediaType(
    *        mediaType="application/json",
    *        @OA\Schema(
    *            type="object",
    *            required={"name", "email", "password", "password_confirmation"},
    *            @OA\Property(
    *                property="name",
    *                type="string",
    *                description="Nome do novo usuário"
    *            ),
    *            @OA\Property(
    *                property="email",
    *                type="string",
    *                description="Email do novo usuário"
    *            ),
    *            @OA\Property(
    *                property="password",
    *                type="string",
    *                description="Senha do novo usuário"
    *           ),
    *           @OA\Property(
    *                property="password_confirmation",
    *                type="string",
    *                description="Confirmação da senha do novo usuário"
    *            )
    *        )
    *       )
    *      ),
    *       @OA\Response(
    *          response=201,
    *          description="OK",
    *          @OA\JsonContent(
    *              type="object",
    *              @OA\Property(
    *                  property="name",
    *                  type="string",
    *                  description="Nome do usuário"
    *              ),
    *              @OA\Property(
    *                  property="email",
    *                  type="string",
    *                  description="Email do usuário"
    *              ),
    *              @OA\Property(
    *                  property="updated_at",
    *                  type="string",
    *                  format="date-time",
    *                  description="Data da última atualização do usuário"
    *              ),
    *              @OA\Property(
    *                  property="created_at",
    *                  type="string",
    *                  format="date-time",
    *                  description="Data de criação do usuário"
    *              ),
    *              @OA\Property(
    *                  property="id",
    *                  type="integer",
    *                  description="ID do usuário"
    *              )
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request",   
    *          @OA\MediaType(
    *              mediaType="application/json",
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
    *                  ),
    *                  @OA\Property(
    *                      property="name",
    *                      type="array",
    *                      @OA\Items(
    *                          type="string",
    *                          description="Mensagem de erro para o campo name"
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
    
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();

        $user = null;
        
        try {
            $user = User::create($data);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao tentar cadastrar o usuário.',
                'errors' => [
                    'system' => ['Erro ao registrar.']
                ]
            ], 500);
        }

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
