<?php

namespace Tests\Feature\User;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TicketSetConcludedTest extends TestCase
{
    private const ROUTE = 'ticket.set.concluded';

    public function setUp(): void
    {
        parent::setUp();
        $this->ticket = Ticket::factory()->create();
    }

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_unauthenticaded()
    {
        $response = $this->putJson(route(self::ROUTE, $this->ticket->getKey()));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_expected_acess_denied()
    {
        $userNoAdmin = User::factory()->create(['is_admin' => false]);
        $response = $this->actingAs($userNoAdmin)->putJson(route(self::ROUTE, $this->ticket->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_expected_http_ok_when_sucess()
    {
        $userAdmin = User::factory()->create();
        $response = $this->actingAs($userAdmin)->putJson(route(self::ROUTE, $this->ticket->getKey()));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
