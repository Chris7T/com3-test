<?php

namespace App\Http\Controllers\Ticket;

use App\Actions\Ticket\TicketSetStatusConcludedAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketSetConcludedController extends Controller
{
    public function __construct(
        private readonly TicketSetStatusConcludedAction $ticketSetStatusConcludedAction
    ) {
    }

    public function __invoke(int $ticketId): JsonResource|JsonResponse
    {
        try {
            $this->ticketSetStatusConcludedAction->execute($ticketId);
            return Response::json(status: HttpFoundationResponse::HTTP_NO_CONTENT);
        } catch (AccessDeniedHttpException $ex) {
            return Response::json(['message' => $ex->getMessage()], $ex->getStatusCode());
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
