<?php

namespace App\Http\Controllers\Service;

use App\Actions\Service\ServiceCreateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ServiceRequest;
use App\Http\Resources\Service\ServiceResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ServiceCreateController extends Controller
{
    public function __construct(
        private readonly ServiceCreateAction $serviceCreateAction
    ) {
    }

    public function __invoke(ServiceRequest $request): JsonResource|JsonResponse
    {
        return ServiceResource::make($this->serviceCreateAction->execute($request->input('description')));
        try {
        } catch (Exception $ex) {
            Log::critical('Controller : ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
