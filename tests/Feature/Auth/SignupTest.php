<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SignupTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

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
            "first_name" => $this->faker->firstName(),
            "last_name" => $this->faker->lastName(),
            "email" => $this->faker->safeEmail(),
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

    /**
     * @return void
     */
    public function test_signup_first_name_validation(): void
    {
        $response = $this->postJson('/api/v1/signup', $this->getPayload(['first_name' => null]));

        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The first name field is required.",
                "errors" => [
                    "first_name" => [
                        "The first name field is required.",
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_signup_last_name_validation(): void
    {
        $response = $this->postJson('/api/v1/signup', $this->getPayload(['last_name' => null]));

        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The last name field is required.",
                "errors" => [
                    "last_name" => [
                        "The last name field is required.",
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_signup_email_validation(): void
    {
        $response = $this->postJson('/api/v1/signup', $this->getPayload(['email' => null]));

        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The email field is required.",
                "errors" => [
                    "email" => [
                        "The email field is required.",
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_signup_email_in_use_validation(): void
    {
        $user = User::factory()->create(['email' => 'teste@mail.com']);
        $response = $this->postJson('/api/v1/signup', $this->getPayload(['email' => $user->email]));

        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The email has already been taken.",
                "errors" => [
                    "email" => [
                        "The email has already been taken.",
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_signup_password_validation(): void
    {
        $response = $this->postJson('/api/v1/signup', $this->getPayload(['password' => null]));

        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The password field is required.",
                "errors" => [
                    "password" => [
                        "The password field is required.",
                    ],
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_signup_password_confirmation_validation(): void
    {
        $response = $this->postJson('/api/v1/signup', $this->getPayload(['password_confirmation' => null]));

        $response->assertStatus(422)
            ->assertExactJson([
                "message" => "The password confirmation does not match.",
                "errors" => [
                    "password" => [
                        "The password confirmation does not match.",
                    ],
                ],
            ]);
    }
}
