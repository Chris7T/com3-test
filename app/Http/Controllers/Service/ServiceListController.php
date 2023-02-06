<?php

namespace App\Http\Controllers\Service;


use App\Actions\Service\ServiceListAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Service\ServiceResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ServiceListController extends Controller
{
    public function __construct(
        private readonly ServiceListAction $serviceListAction
    ) {
    }

    public function __invoke(): JsonResource|JsonResponse
    {
        try {
            return ServiceResource::collection($this->serviceListAction->execute());
        } catch (Exception $ex) {
            Log::critical('Controller : ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
