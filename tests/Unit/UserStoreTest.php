<?php

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Teste para certificar se o cadastro de um usuário foi efetuado com sucesso.', function () {
    $response = $this->postJson('/api/v1/user', [
        'name' => 'Teste',
        'email' => 'teste@gmail.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $response->assertStatus(201)
    ->assertJson(fn (AssertableJson $json) =>
        $json->where('name', 'Teste')
             ->where('email',('teste@gmail.com'))
             ->missing('password')
             ->etc()
    );
});

test('Teste para certificar se ao cadastrar um usuário com email já registrado o mesmo é bloqueado e informado.', function () {
    $responseAttemptOne = $this->postJson('/api/v1/user', [
        'name' => 'Teste',
        'email' => 'teste@gmail.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $responseAttemptTwo = $this->postJson('/api/v1/user', [
        'name' => 'Teste',
        'email' => 'teste@gmail.com',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $responseAttemptOne->assertStatus(201)
    ->assertJson(fn (AssertableJson $json) =>
        $json->where('name', 'Teste')
             ->where('email',('teste@gmail.com'))
             ->missing('password')
             ->etc()
    );

    $responseAttemptTwo->assertStatus(422)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.email', ['O campo email já está sendo utilizado.'])
        ->etc()
    );
});

test('Teste para certificar se ao cadastrar um usuário sem o password e password_confirmation o mesmo é bloqueado e informado.', function () {
    $response = $this->postJson('/api/v1/user', [
        'name' => 'Teste',
        'email' => 'teste@gmail.com'
    ]);

    $response->assertStatus(422)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.password', ['O campo senha é obrigatório.'])
        ->etc()
    );

});

test('Teste para certificar se ao cadastrar um usuário com o password divergênte do password_confirmation o mesmo é bloqueado e informado.', function () {
    $response = $this->postJson('/api/v1/user', [
        'name' => 'Teste',
        'email' => 'teste@gmail.com',
        'password' => '123456789',
        'password_confirmation' => '12345678',
    ]);

    $response->assertStatus(422)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.password', ['O campo senha de confirmação não confere.'])
        ->etc()
    );

});

test('Teste para certificar se ao cadastrar um usuário sem dados o mesmo é bloqueado e informado.', function () {
    $response = $this->postJson('/api/v1/user', [
        
    ]);

    $response->assertStatus(422)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.name', ['O campo nome é obrigatório.'])
        ->where('errors.email', ['O campo email é obrigatório.'])
        ->where('errors.password', ['O campo senha é obrigatório.'])
        ->etc()
    );

});

test('Teste para certificar se ao cadastrar um usuário com email não válido (fora do padrão) o mesmo é bloqueado e informado.', function () {
    $response = $this->postJson('/api/v1/user', [
        'name' => 'Teste',
        'email' => 'apenasumtexxto',
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]);

    $response->assertStatus(422)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('message')
        ->where('errors.email', ['O campo email deve ser um endereço de e-mail válido.'])
        ->etc()
    );
});


