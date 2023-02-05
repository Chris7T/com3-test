<?php

namespace App\Http\Controllers;

use App\Actions\User\UserLoginAction;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\User\LoginResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        private readonly UserLoginAction $userLoginAction
    ) {
    }

    public function login(LoginRequest $request): JsonResource|JsonResponse
    {
        try {
            return LoginResource::make($this->userLoginAction->execute($request->validated()));
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
