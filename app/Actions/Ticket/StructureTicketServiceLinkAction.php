<?php

namespace App\Actions\Ticket;

class StructureTicketServiceLinkAction
{
    public function execute(
        int $ticketId,
        array $serviceIds
    ): array {
        $ticketServices = [];
        foreach ($serviceIds as $serviceId) {
            $ticketServices[] = [
                'ticket_id' => $ticketId,
                'service_id' => $serviceId
            ];
        }

        return $ticketServices;
    }
}
