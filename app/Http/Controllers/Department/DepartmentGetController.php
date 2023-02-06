<?php

namespace App\Http\Controllers\Department;

use App\Actions\Department\DepartmentGetAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Department\DepartmentResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentGetController extends Controller
{
    public function __construct(
        private readonly DepartmentGetAction $departmentCreateAction
    ) {
    }

    public function __invoke(int $id): JsonResource|JsonResponse
    {
        try {
            return DepartmentResource::make($this->departmentCreateAction->execute($id));
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
