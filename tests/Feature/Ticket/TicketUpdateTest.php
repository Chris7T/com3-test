<?php

namespace Tests\Feature\User;

use App\Models\Department;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\TicketService;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TicketUpdateTest extends TestCase
{
    private const ROUTE = 'ticket.update';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->ticket = Ticket::factory()->create();
        TicketService::factory()->create(['ticket_id' => $this->ticket->getKey()]);
        TicketDepartment::factory()->create(['ticket_id' => $this->ticket->getKey()]);
        $this->service = Service::factory()->create();
        $this->department = Department::factory()->create();
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
        $response = $this->putJson(route(self::ROUTE, $this->ticket->getKey()), $request);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_expected_unprocessable_entity_exception_when_description_is_null()
    {
        $response = $this->actingAs($this->user)->putJson(route(self::ROUTE, $this->ticket->getKey(), []));

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
        $response = $this->actingAs($this->user)->putJson(route(self::ROUTE, $this->ticket->getKey()), $request);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['description'])
            ->assertJson([
                'errors' => [
                    'description' => ['The description must not be greater than 100 characters.'],
                ],
            ]);
    }

    public function test_expected_http_ok_when_sucess()
    {
        $request = [
            'description' => Str::random(50),
            'services' => [$this->service->getKey()],
            'departments' => [$this->department->getKey()]
        ];
        $response = $this->actingAs($this->user)->putJson(route(self::ROUTE, $this->ticket->getKey()), $request);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
