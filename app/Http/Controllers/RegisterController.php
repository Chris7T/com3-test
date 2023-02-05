<?php

namespace App\Http\Controllers;

use App\Actions\User\UserRegisterAction;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\LoginResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RegisterController extends Controller
{
    public function __construct(
        private readonly UserRegisterAction $userRegisterAction
    ) {
    }

    public function register(RegisterRequest $request): JsonResource|JsonResponse
    {
        return LoginResource::make($this->userRegisterAction->execute($request->validated()));
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
