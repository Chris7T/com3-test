<?php

namespace App\Http\Controllers\Ticket;

use App\Actions\Ticket\TicketDeleteAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketDeleteController extends Controller
{
    public function __construct(
        private readonly TicketDeleteAction $ticketDeleteAction
    ) {
    }

    public function __invoke(int $id): JsonResource|JsonResponse
    {
        $this->ticketDeleteAction->execute($id);
        try {

            return Response::json(status: HttpFoundationResponse::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $ex) {

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
