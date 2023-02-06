<?php

namespace App\Actions\Ticket;

use App\Actions\User\UserCheckIsAdminPermissionAction;
use App\Repositories\Ticket\TicketRepositoryInterface;

class TicketSetStatusConcludedAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly TicketGetAction $ticketGetAction,
        private readonly UserCheckIsAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(int $id): void
    {
        $this->userCheckAdminPermissionAction->execute();
        $this->ticketGetAction->execute($id);
        $this->ticketRepository->setStatusConcluded($id);
    }
}
