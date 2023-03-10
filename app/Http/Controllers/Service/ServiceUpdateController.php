<?php

namespace App\Http\Controllers\Service;

use App\Actions\Service\ServiceUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ServiceRequest;
use App\Http\Resources\Service\ServiceResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceUpdateController extends Controller
{
    public function __construct(
        private readonly ServiceUpdateAction $serviceUpdateAction
    ) {
    }

    public function __invoke(ServiceRequest $request, int $serviceId): JsonResource|JsonResponse
    {
        try {
            ServiceResource::make($this->serviceUpdateAction->execute($serviceId, $request->input('description')));
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
