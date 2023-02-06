<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DepartmentCreateTest extends TestCase
{
    private const ROUTE = 'department.register';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_unauthenticaded()
    {
        $request = [
            'description' => Str::random(50),
        ];
        $response = $this->postJson(route(self::ROUTE), $request);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_expected_unprocessable_entity_exception_when_description_is_null()
    {
        $response = $this->actingAs($this->user)->postJson(route(self::ROUTE, []));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['description'])
            ->assertJson([
                'errors' => [
                    'description' => ['The description field is required.'],
                ],
            ]);
    }

    public function test_expected_unprocessable_entity_exception_when_description_bigger_than_100()
    {
        $request = [
            'description' => Str::random(101),
        ];
        $response = $this->actingAs($this->user)->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['description'])
            ->assertJson([
                'errors' => [
                    'description' => ['The description must not be greater than 100 characters.'],
                ],
            ]);
    }

    public function test_expected_http_created_when_sucess()
    {
        $request = [
            'description' => Str::random(50),
        ];
        $response = $this->actingAs($this->user)->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'description' => $request['description'],
                ],
            ]);
    }
}
