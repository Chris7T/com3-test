<?php

namespace App\Actions\Comment;

use App\Actions\Ticket\TicketSetStatusProgressAction;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CommentCreateAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly TicketSetStatusProgressAction $ticketSetStatusProgressAction
    ) {
    }

    public function execute(string $description, int $ticketId): Comment
    {
        $this->ticketSetStatusProgressAction->execute($ticketId);
        $comment = $this->commentRepository->createComment($description, $ticketId);
        Cache::put("comment-{$comment->getKey()}", $comment, config('cache.time.one_month'));

        return $comment;
    }
}
