<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Teste para certificar se ao solicitar os dados de um usuário é retornado com sucesso.', function () {
    User::factory()->count(10)->create();
    
    $id_random = random_int(1, 10);

    $response = $this->get('/api/v1/user/'.$id_random);

    $response->assertStatus(200)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('name')
             ->has('email')
             ->has('id')
             ->missing('password')
             ->etc()
    );
});

test('Teste para certificar se ao solicitar os dados de um usuário NÂO existente é retornado erro e um aviso.', function () {
    User::factory()->count(10)->create();
    
    $id_random = random_int(100, 1000);

    $response = $this->get('/api/v1/user/'.$id_random);

    $response->assertStatus(404)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.user', ['Erro ao recuperar as informações do usuário.'])
        ->etc()
    );
});
