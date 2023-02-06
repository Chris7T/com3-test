<?php

namespace App\Actions\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CommentDeleteAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly CommentGetAction $commentGetAction,
    ) {
    }

    public function execute(int $id): void
    {
        $this->commentGetAction->execute($id);
        $this->commentRepository->deleteCommentById($id);
        Cache::forget("comment-{$id}");
        Cache::forget('comment-list');
    }
}
