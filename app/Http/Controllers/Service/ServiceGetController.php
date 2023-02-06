<?php

namespace App\Http\Controllers\Service;

use App\Actions\Service\ServiceGetAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Service\ServiceResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceGetController extends Controller
{
    public function __construct(
        private readonly ServiceGetAction $serviceCreateAction
    ) {
    }

    public function __invoke(int $id): JsonResource|JsonResponse
    {
        try {
            return ServiceResource::make($this->serviceCreateAction->execute($id));
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
