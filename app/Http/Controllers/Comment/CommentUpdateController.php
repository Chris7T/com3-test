<?php

namespace App\Http\Controllers\Comment;

use App\Actions\Comment\CommentUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\Comment\CommentResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentUpdateController extends Controller
{
    public function __construct(
        private readonly CommentUpdateAction $commentUpdateAction
    ) {
    }

    public function __invoke(CommentRequest $request, int $commentId): JsonResource|JsonResponse
    {
        try {
            CommentResource::make(
                $this->commentUpdateAction->execute(
                    $commentId,
                    $request->input('description'),
                    $request->input('ticket_id')
                )
            );
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
