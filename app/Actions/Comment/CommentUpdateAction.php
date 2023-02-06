<?php

namespace App\Actions\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;

class CommentUpdateAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly CommentGetAction $commentGetAction,
    ) {
    }

    public function execute(int $id, string $description, int $ticketId): void
    {
        $this->commentGetAction->execute($id);

        $this->commentRepository->update($id, $description, $ticketId);
    }
}
