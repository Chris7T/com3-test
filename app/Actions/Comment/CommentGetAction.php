<?php

namespace App\Actions\Comment;

use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentGetAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    public function execute(int $id): Comment
    {
        $comment = Cache::remember(
            "comment-{$id}",
            config('cache.one_day'),
            fn () => $this->commentRepository->getCommentById($id)
        );

        if (is_null($comment)) {
            throw new NotFoundHttpException('Comment not found');
        }

        return $comment;
    }
}
