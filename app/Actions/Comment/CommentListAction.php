<?php

namespace App\Actions\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CommentListAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    public function execute(): LengthAwarePaginator
    {
        return Cache::remember(
            'comment-list',
            config('cache.one_day'),
            function () {
                return $this->commentRepository->list();
            }
        );
    }
}
