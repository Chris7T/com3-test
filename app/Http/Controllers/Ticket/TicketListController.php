<?php

namespace App\Http\Controllers\Ticket;


use App\Actions\Ticket\TicketListAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Ticket\TicketResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class TicketListController extends Controller
{
    public function __construct(
        private readonly TicketListAction $ticketListAction
    ) {
    }

    public function __invoke(): JsonResource|JsonResponse
    {
        try {
            return TicketResource::collection($this->ticketListAction->execute());
        } catch (Exception $ex) {
            Log::critical('Controller : ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
