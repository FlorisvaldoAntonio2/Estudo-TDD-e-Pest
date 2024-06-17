<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

beforeEach(function () {
   $this->user = User::factory()->create([
        'email' => 'testelaraval@gmail.com',
        'password' => bcrypt('12345678')
   ]);
});

test('Teste para certificar se ao passar as credencias corretas o login foi efetuado com sucesso.', function () {
    $response = $this->postJson('/api/v1/auth', [
        'email' => 'testelaraval@gmail.com',
        'password' => '12345678',
    ]);

    $response->assertStatus(200)
    ->assertJson(fn (AssertableJson $json) =>
        $json->where('email',('testelaraval@gmail.com'))
             ->has('token')
             ->has('id')
             ->missing('password')
    );
});

test('Teste para certificar se ao passar as credencias incorretas o login é rejeitado e informado sobre o fato.', function () {
    $response = $this->postJson('/api/v1/auth', [
        'email' => 'nãoexistel@gmail.com',
        'password' => '12345678',
    ]);

    $response->assertStatus(401)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.user', ['Erro ao recuperar o usuário.'])
        ->etc()
    );
});

test('Teste para certificar se ao passar as credencias sem o email o login é rejeitado e informado sobre o fato.', function () {
    $response = $this->postJson('/api/v1/auth', [
        'email2' => 'nãoexistel@gmail.com',
        'password' => '12345678',
    ]);

    $response->assertStatus(422)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.email', ['O campo email é obrigatório.'])
        ->etc()
    );
});

test('Teste para certificar se ao passar as credencias sem o password o login é rejeitado e informado sobre o fato.', function () {
    $response = $this->postJson('/api/v1/auth', [
        'email' => 'nãoexistel@gmail.com',
        'password2' => '12345678',
    ]);

    $response->assertStatus(422)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.password', ['O campo senha é obrigatório.'])
        ->etc()
    );
});
