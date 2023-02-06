<?php

namespace App\Actions\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;

class LinkTicketDepartmentAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly StructureTicketDepartmentLinkAction $structureTicketDepartmentLinkAction
    ) {
    }

    public function execute(
        int $ticketId,
        array $departmentIds
    ): void {
        $currentIds = $this->ticketRepository->getlinkedDepartment($ticketId)->toArray();
        $unlinkDepartmentsIds = array_diff($currentIds, $departmentIds);
        $linkDepartmentsIds = array_diff($departmentIds, $currentIds);
        $structuredDepartmentsToLink = $this->structureTicketDepartmentLinkAction->execute($ticketId, $linkDepartmentsIds);

        $this->ticketRepository->unlinkDepartment($ticketId, $unlinkDepartmentsIds);
        $this->ticketRepository->linkDepartment($ticketId, $structuredDepartmentsToLink);
    }
}
