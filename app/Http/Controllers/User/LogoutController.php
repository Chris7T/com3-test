<?php

namespace App\Http\Controllers\User;

use App\Actions\User\UserLogoutAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class LogoutController extends Controller
{
    public function __construct(
        private readonly UserLogoutAction $userLogoutAction
    ) {
    }

    public function perform(): JsonResource|JsonResponse
    {
        try {
            $this->userLogoutAction->execute();
            return Response::json(status: HttpFoundationResponse::HTTP_NO_CONTENT);
        } catch (Exception $ex) {
            Log::critical('Controller : ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
