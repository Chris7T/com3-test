<?php

namespace App\Http\Controllers\Comment;


use App\Actions\Comment\CommentListAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class CommentListController extends Controller
{
    public function __construct(
        private readonly CommentListAction $commentListAction
    ) {
    }

    public function __invoke(): JsonResource|JsonResponse
    {
        return CommentResource::collection($this->commentListAction->execute());
        try {
        } catch (Exception $ex) {
            Log::critical('Controller : ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
