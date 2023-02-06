<?php

namespace App\Http\Controllers\Department;

use App\Actions\Department\DepartmentCreateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Department\DepartmentRequest;
use App\Http\Resources\Department\DepartmentResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DepartmentCreateController extends Controller
{
    public function __construct(
        private readonly DepartmentCreateAction $departmentCreateAction
    ) {
    }

    public function __invoke(DepartmentRequest $request): JsonResource|JsonResponse
    {
        return DepartmentResource::make($this->departmentCreateAction->execute($request->input('description')));
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
