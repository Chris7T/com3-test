<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository implements CommentRepositoryInterface
{
    public function __construct(
        private readonly Comment $model
    ) {
    }

    public function getCommentById(int $id): ?Comment
    {
        return $this->model->find($id);
    }

    public function createComment(string $description, int $ticketId): Comment
    {
        return $this->model->create(
            [
                'description' => $description,
                'ticket_id' => $ticketId
            ]
        );
    }

    public function list(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }

    public function listByTicket(int $ticketId): LengthAwarePaginator
    {
        return $this->model->where('ticket_id', $ticketId)->paginate();
    }

    public function update(int $id, string $description, int $ticketId): void
    {
        $this->model->find($id)->update(
            [
                'description' => $description,
                'ticket_id' => $ticketId
            ]
        );
    }

    public function deleteCommentById(int $id): void
    {
        $this->model->find($id)->delete();
    }
}
