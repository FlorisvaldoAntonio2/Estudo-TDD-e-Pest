<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('Teste para certificar se ao deletar um usuário é realizado sucesso.', function () {
    $user = User::factory()->create();

    $response = $this->delete('/api/v1/user/'.$user->id);

    $response->assertStatus(200)
    ->assertJson(fn (AssertableJson $json) =>
        $json->where('message', 'Usuário deletado com sucesso.')
    );
});

test('Teste para certificar se ao deletar um usuário que não existe o usuário é informado.', function () {
    $user = User::factory()->create();

    $response = $this->delete('/api/v1/user/'. Random_int(100, 1000));

    $response->assertStatus(404)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.user', ['Erro ao recuperar as informações do usuário.'])
        ->etc()
    );
});
