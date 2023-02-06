<?php

namespace App\Http\Controllers\Ticket;

use App\Actions\Ticket\TicketCreateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Resources\Ticket\TicketResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TicketCreateController extends Controller
{
    public function __construct(
        private readonly TicketCreateAction $ticketCreateAction
    ) {
    }

    public function __invoke(TicketRequest $request): JsonResource|JsonResponse
    {
        return TicketResource::make($this->ticketCreateAction->execute(
            $request->input('description'),
            $request->input('services'),
            $request->input('departments'),
        ));
        try {
        } catch (AccessDeniedHttpException $ex) {
            return Response::json(['message' => $ex->getMessage()], $ex->getStatusCode());
        } catch (Exception $ex) {
            Log::critical('Controller : ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
