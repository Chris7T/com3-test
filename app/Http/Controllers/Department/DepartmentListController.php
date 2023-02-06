<?php

namespace App\Http\Controllers\Department;


use App\Actions\Department\DepartmentListAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Department\DepartmentResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class DepartmentListController extends Controller
{
    public function __construct(
        private readonly DepartmentListAction $departmentListAction
    ) {
    }

    public function __invoke(): JsonResource|JsonResponse
    {
        try {
            return DepartmentResource::collection($this->departmentListAction->execute());
        } catch (Exception $ex) {
            Log::critical('Controller : ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
