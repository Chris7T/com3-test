<?php

namespace App\Http\Controllers\Service;

use App\Actions\Service\ServiceDeleteAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceDeleteController extends Controller
{
    public function __construct(
        private readonly ServiceDeleteAction $serviceDeleteAction
    ) {
    }

    public function __invoke(int $id): JsonResource|JsonResponse
    {
        try {
            $this->serviceDeleteAction->execute($id);

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
