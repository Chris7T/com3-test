<?php

namespace Tests\Feature\User;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentGetTest extends TestCase
{
    private const ROUTE = 'comment.get';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->comment = Comment::factory()->create();
    }

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_unauthenticaded()
    {
        $response = $this->getJson(route(self::ROUTE, $this->comment->getKey()));

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
        $response = $this->actingAs($this->user)->getJson(route(self::ROUTE, $this->comment->getKey()));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'description' => $this->comment->description,
                    'ticket_id' => $this->comment->ticket_id,
                ],
            ]);
    }
}
