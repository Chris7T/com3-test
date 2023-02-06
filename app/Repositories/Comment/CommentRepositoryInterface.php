<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    public function getCommentById(int $id): ?Comment;
    public function createComment(string $description, int $ticketId): Comment;
    public function list(): LengthAwarePaginator;
    public function listByTicket(int $ticketId): LengthAwarePaginator;
    public function update(int $id, string $description, int $ticketId): void;
    public function deleteCommentById(int $id): void;
}
