<?php

namespace App\Actions\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;

class LinkTicketServiceAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly StructureTicketServiceLinkAction $structureTicketServiceLinkAction
    ) {
    }

    public function execute(
        int $ticketId,
        array $serviceIds
    ): void {
        $currentIds = $this->ticketRepository->getlinkedService($ticketId)->toArray();
        $unlinkServicesIds = array_diff($currentIds, $serviceIds);
        $linkServicesIds = array_diff($serviceIds, $currentIds);
        $structuredServicesToLink = $this->structureTicketServiceLinkAction->execute($ticketId, $linkServicesIds);

        $this->ticketRepository->unlinkService($ticketId, $unlinkServicesIds);
        $this->ticketRepository->linkService($ticketId, $structuredServicesToLink);
    }
}
