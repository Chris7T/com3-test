<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private const ROUTE = 'user.login';

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_unprocessable_entity_exception_when_data_is_null()
    {
        $response = $this->postJson(route(self::ROUTE, []));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'password'])
            ->assertJson([
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
            ]);
    }

    public function test_expected_unprocessable_entity_exception_when_password_digits_is_not_8()
    {
        $request = [
            'password' => '123',
        ];
        $response = $this->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password'])
            ->assertJson([
                'errors' => [
                    'password' => ['The password must be 8 digits.'],
                ],
            ]);
    }

    public function test_expected_unprocessable_entity_exception_when_email_format_is_invalid()
    {
        $request = [
            'email' => 'teste',
        ];
        $response = $this->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'errors' => [
                    'email' => ['The email must be a valid email address.'],
                ],
            ]);
    }

    public function test_expected_token_when_sucess()
    {
        $user = User::factory()->create(
            [
                'email' => 'email@hotmail.com',
                'password' => '12345678'
            ]
        );

        $request = [
            'email' => $user->email,
            'password' => '12345678',
        ];
        $response = $this->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee(['token']);
    }
}
