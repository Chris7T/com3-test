<?php

namespace App\Http\Controllers\Department;

use App\Actions\Department\DepartmentUpdateAction;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentUpdateController extends Controller
{
    public function __construct(
        private readonly DepartmentUpdateAction $departmentUpdateAction
    ) {
    }

    public function __invoke(DepartmentRequest $request, int $departmentId): JsonResource|JsonResponse
    {
        try {
            DepartmentResource::make($this->departmentUpdateAction->execute($departmentId, $request->input('description')));
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
