<?php

namespace App\Repositories\Ticket;

use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TicketRepositoryInterface
{
    public function getTicketById(int $id): ?Ticket;
    public function createTicket(string $description): Ticket;
    public function list(): LengthAwarePaginator;
    public function update(int $id, string $description): void;
    public function deleteTicketById(int $id): void;
    public function linkService(int $id, array $serviceIds): void;
    public function linkDepartment(int $id, array $departmentIds): void;
    public function unlinkDepartment(int $id, array $departmentIds): void;
    public function unlinkService(int $id, array $serviceIds): void;
    public function getlinkedDepartment(int $id): Collection;
    public function getlinkedService(int $id): Collection;
    public function setStatusConcluded(int $id): void;
    public function setStatusPending(int $id): void;
}
