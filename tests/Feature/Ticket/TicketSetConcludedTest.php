<?php

namespace Tests\Feature\User;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TicketSetConcludedTest extends TestCase
{
    private const ROUTE = 'ticket.set.concluded';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->ticket = Ticket::factory()->create();
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
        $response = $this->putJson(route(self::ROUTE, $this->ticket->getKey()));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_expected_http_ok_when_sucess()
    {
        $response = $this->actingAs($this->user)->putJson(route(self::ROUTE, $this->ticket->getKey()));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
