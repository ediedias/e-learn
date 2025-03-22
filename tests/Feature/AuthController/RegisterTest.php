<?php

namespace Tests\Feature\AuthController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_a_student(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'student'
        ])->assertCreated();

        $this->assertDataBaseHas('users', [
            'email' => 'john.doe@test.com'
        ]);
    }

    public function test_return_422_if_invalid_value(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'john.doe@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'student'
        ])->assertUnprocessable();
    }

    public function test_return_422_if_invalid_or_missing_field(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'age' => 13,
            'password' => 'password',
            'password_confirmation' => 'password'
        ])->assertUnprocessable();
    }

    public function test_return_422_if_user_exists(): void
    {
        User::factory()->create(['email' => 'john.doe@test.com']);

        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'john.doe@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
    }
}