<?php

namespace App\Actions\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;

class TicketUpdateAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly TicketGetAction $ticketGetAction,
        private readonly LinkTicketServiceAction $linkTicketServiceAction,
        private readonly LinkTicketDepartmentAction $linkTicketDepartmentAction
    ) {
    }

    public function execute(
        int $id,
        string $description,
        array $serviceIds,
        array $departmentIds
    ): void {
        $this->ticketGetAction->execute($id);
        $this->linkTicketServiceAction->execute($id, $serviceIds);
        $this->linkTicketDepartmentAction->execute($id, $departmentIds);
        $this->ticketRepository->update($id, $description);
    }
}
