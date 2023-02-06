<?php

namespace App\Actions\Comment;

use App\Actions\Ticket\TicketSetStatusPendingAction;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CommentCreateAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly TicketSetStatusPendingAction $ticketSetStatusPendingAction
    ) {
    }

    public function execute(string $description, int $ticketId): Comment
    {
        $this->ticketSetStatusPendingAction->execute($ticketId);
        $comment = $this->commentRepository->createComment($description, $ticketId);
        Cache::put("comment-{$comment->getKey()}", $comment, config('cache.time.one_month'));

        return $comment;
    }
}
