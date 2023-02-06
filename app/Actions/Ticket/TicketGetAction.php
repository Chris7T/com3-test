<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TicketGetAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository
    ) {
    }

    public function execute(int $id): Ticket
    {
        $ticket = Cache::remember(
            "Ticket-{$id}",
            config('cache.one_day'),
            fn () => $this->ticketRepository->getTicketById($id)
        );

        if (is_null($ticket)) {
            throw new NotFoundHttpException('Ticket not found');
        }

        return $ticket;
    }
}
