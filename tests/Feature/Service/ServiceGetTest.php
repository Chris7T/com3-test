<?php

namespace Tests\Feature\User;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ServiceGetTest extends TestCase
{
    private const ROUTE = 'service.get';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->service = Service::factory()->create();
    }

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_unauthenticaded()
    {
        $response = $this->getJson(route(self::ROUTE, $this->service->getKey()));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_expected_not_found_exception_when_data_does_not_exists()
    {
        $notExist = 0;
        $response = $this->actingAs($this->user)->getJson(route(self::ROUTE, $notExist));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }


    public function test_expected_http_ok_when_sucess()
    {
        $response = $this->actingAs($this->user)->getJson(route(self::ROUTE, $this->service->getKey()));

        $response->assertStatus(Response::HTTP_OK);
    }
}
