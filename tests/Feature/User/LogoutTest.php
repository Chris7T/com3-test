<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    private const ROUTE = 'user.logout';

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_unauthenticaded()
    {
        $response = $this->getJson(route(self::ROUTE));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_expected_token_when_sucess()
    {
        $user = User::factory()->create();
        $token = Sanctum::actingAs($user, ['*']);

        $response = $this->actingAs($token)->getJson(route(self::ROUTE));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
