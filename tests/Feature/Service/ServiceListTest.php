<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ServiceListTest extends TestCase
{
    private const ROUTE = 'service.list';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_http_created_when_sucess()
    {
        $response = $this->actingAs($this->user)->getJson(route(self::ROUTE));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJson([
                'data' => [],
            ]);
    }
}
