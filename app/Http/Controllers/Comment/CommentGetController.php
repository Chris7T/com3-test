<?php

namespace App\Http\Controllers\Comment;

use App\Actions\Comment\CommentGetAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentGetController extends Controller
{
    public function __construct(
        private readonly CommentGetAction $commentCreateAction
    ) {
    }

    public function __invoke(int $id): JsonResource|JsonResponse
    {
        try {
            return CommentResource::make($this->commentCreateAction->execute($id));
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
