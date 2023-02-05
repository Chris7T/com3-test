<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    private const ROUTE = 'user.register';

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_unprocessable_entity_exception_when_data_is_null()
    {
        $response = $this->postJson(route(self::ROUTE, []));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'password', 'phone', 'name'])
            ->assertJson([
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                    'phone' => ['The phone field is required.'],
                    'name' => ['The name field is required.'],
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

    public function test_expected_unprocessable_entity_exception_when_phone_digits_is_not_12()
    {
        $request = [
            'phone' => '123',
        ];
        $response = $this->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['phone'])
            ->assertJson([
                'errors' => [
                    'phone' => ['The phone must be 12 digits.'],
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

    public function test_expected_unprocessable_entity_exception_when_password_is_not_confirmed()
    {
        $request = [
            'password' => '12345678',
        ];
        $response = $this->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password'])
            ->assertJson([
                'errors' => [
                    'password' => ['The password confirmation does not match.'],
                ],
            ]);
    }

    public function test_expected_unprocessable_entity_exception_when_email_is_not_unique()
    {
        $user = User::factory()->create(
            [
                'email' => 'email@hotmail.com',
            ]
        );
        $request = [
            'email' => $user->email,
        ];
        $response = $this->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ]);
    }

    public function test_expected_token_when_sucess()
    {
        $request = [
            'email' => 'email@hotmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'phone' => '999999999999',
            'name' => 'name',
        ];
        $response = $this->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee(['token']);
    }
}
