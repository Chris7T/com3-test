<?php

namespace App\Http\Controllers\Comment;

use App\Actions\Comment\CommentCreateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\Comment\CommentResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class CommentCreateController extends Controller
{
    public function __construct(
        private readonly CommentCreateAction $commentCreateAction
    ) {
    }

    public function __invoke(CommentRequest $request): JsonResource|JsonResponse
    {
        return CommentResource::make($this->commentCreateAction->execute(
            $request->input('description'),
            $request->input('ticket_id')
        ));
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
