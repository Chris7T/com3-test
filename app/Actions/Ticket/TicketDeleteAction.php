<?php

namespace App\Actions\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class TicketDeleteAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly TicketGetAction $ticketGetAction,
    ) {
    }

    public function execute(int $id): void
    {
        $this->ticketGetAction->execute($id);
        $this->ticketRepository->deleteTicketById($id);
        Cache::forget("ticket-{$id}");
        Cache::forget('ticket-list');
    }
}
