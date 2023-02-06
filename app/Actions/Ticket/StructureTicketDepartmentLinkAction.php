<?php

namespace App\Actions\Ticket;

class StructureTicketDepartmentLinkAction
{
    public function execute(
        int $ticketId,
        array $departmentIds
    ): array {
        $ticketDepartment = [];
        foreach ($departmentIds as $departmentId) {
            $ticketDepartment[] = [
                'ticket_id' => $ticketId,
                'department_id' => $departmentId
            ];
        }

        return $ticketDepartment;
    }
}
