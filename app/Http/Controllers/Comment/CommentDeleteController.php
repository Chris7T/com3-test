<?php

namespace App\Http\Controllers\Comment;

use App\Actions\Comment\CommentDeleteAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentDeleteController extends Controller
{
    public function __construct(
        private readonly CommentDeleteAction $commentDeleteAction
    ) {
    }

    public function __invoke(int $id): JsonResource|JsonResponse
    {
        try {
            $this->commentDeleteAction->execute($id);

            return Response::json(status: HttpFoundationResponse::HTTP_NO_CONTENT);
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
