<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use DatabaseTransactions;

    public function __contruct()
    {
        $this->setUpFaker();
    }

    /**
     * @param array $data
     * @return array
     */
    public function getPayload(array $data = []): array
    {
        return array_merge([
            "first_name" => "Fulano",
            "last_name" => "Ciclano",
            "email" => "fulano@gmail.com",
            "password" => "123456",
            "password_confirmation" => "123456",
        ], $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup_success(): void
    {
        $response = $this->postJson('/api/v1/signup', $this->getPayload());
        $response->assertStatus(201)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "created_at",
                "updated_at",
                "token"
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_signup_validation(): void
    {
        $response = $this->postJson('/api/v1/signup', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "email",
                    "password",
                    "first_name",
                    "last_name",
                ],
            ]);
    }
}
