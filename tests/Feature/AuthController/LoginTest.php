<?php

namespace Tests\Feature\AuthController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            "email" => $user->email,
            "password" => "password"
        ])
        ->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'email',
                    'type'
                ]
            ],
            'meta' => [
                'token'
            ]
        ])
        ->assertOk();
    }    

    public function test_return_401_if_wrong_credentials(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            "email" => $user->email,
            "password" => "wrongpassword"
        ])
        ->assertUnauthorized();
    }

    public function test_return_422_if_empty_email(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            "email" => "",
            "password" => "wrongpassword"
        ])
        ->assertUnprocessable();
    }

    public function test_return_422_if_empty_password(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            "email" => $user->email,
            "password" => ""
        ])
        ->assertUnprocessable();
    }
}