<?php

namespace Tests\Feature\User;

use App\Models\Department;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TicketCreateTest extends TestCase
{
    private const ROUTE = 'ticket.register';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
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
        $response = $this->postJson(route(self::ROUTE), $request);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_expected_unprocessable_entity_exception_when_data_is_null()
    {
        $response = $this->actingAs($this->user)->postJson(route(self::ROUTE, []));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['description', 'services', 'departments'])
            ->assertJson([
                'errors' => [
                    'description' => ['The description field is required.'],
                    'services' => ['The services field is required.'],
                    'departments' => ['The departments field is required.'],
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

    public function test_expected_unprocessable_entity_exception_when_data_is_invalid()
    {
        $request = [
            'services' => [0],
            'departments' => [0]
        ];
        $response = $this->actingAs($this->user)->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['services.0', 'departments.0'])
            ->assertJson([
                'errors' => [
                    'services.0' => ['The selected services.0 is invalid.'],
                    'departments.0' => ['The selected departments.0 is invalid.'],
                ],
            ]);
    }

    public function test_expected_http_created_when_sucess()
    {
        $request = [
            'description' => Str::random(50),
            'services' => [$this->service->getKey()],
            'departments' => [$this->department->getKey()]
        ];
        $response = $this->actingAs($this->user)->postJson(route(self::ROUTE, $request));

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'services' =>  [
                        [
                            'description' => $this->service->description
                        ]
                    ],
                    'departments' =>  [
                        [
                            'description' => $this->department->description
                        ]
                    ]
                ]
            ]);
    }
}
