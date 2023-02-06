<?php

namespace App\Actions\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;

class TicketSetStatusProgressAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly TicketGetAction $ticketGetAction,
    ) {
    }

    public function execute(int $id): void
    {
        $this->ticketGetAction->execute($id);
        $this->ticketRepository->setStatusConcluded($id);
    }
}
