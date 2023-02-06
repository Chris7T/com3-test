<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TicketCreateAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly LinkTicketServiceAction $linkTicketServiceAction,
        private readonly LinkTicketDepartmentAction $linkTicketDepartmentAction

    ) {
    }

    public function execute(string $description, array $serviceIds, array $departmentIds): Ticket
    {
        $userId = Auth::id();
        $ticket = $this->ticketRepository->createTicket($description, $userId);
        $this->linkTicketServiceAction->execute($ticket->getKey(), $serviceIds);
        $this->linkTicketDepartmentAction->execute($ticket->getKey(), $departmentIds);
        Cache::put("ticket-{$ticket->getKey()}", $ticket, config('cache.time.one_month'));

        return $ticket;
    }
}
