<?php

namespace App\Repositories\Ticket;

use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        private readonly Ticket $model
    ) {
    }

    public function getTicketById(int $id): ?Ticket
    {
        return $this->model->find($id);
    }

    public function createTicket(string $description): Ticket
    {
        return $this->model->create(
            [
                'description' => $description,
            ]
        );
    }
    public function list(): LengthAwarePaginator
    {
        return $this->model->paginate();
    }

    public function update(int $id, string $description): void
    {
        $this->model->find($id)->update(['description' => $description]);
    }

    public function deleteTicketById(int $id): void
    {
        $data = $this->model->find($id);
        $data->ticketService()->delete();
        $data->ticketDepartment()->delete();
        $data->delete();
    }

    public function linkService(int $id, array $serviceIdsStrutured): void
    {
        $this->model->find($id)->ticketService()->createMany($serviceIdsStrutured);
    }

    public function linkDepartment(int $id, array $departmentIdsStrutured): void
    {
        $this->model->find($id)->ticketDepartment()->createMany($departmentIdsStrutured);
    }

    public function unlinkDepartment(int $id, array $departmentIds): void
    {
        $this->model->find($id)
            ->ticketDepartment()
            ->whereIn('department_id', $departmentIds)
            ->delete();
    }

    public function unlinkService(int $id, array $serviceIds): void
    {
        $this->model->find($id)
            ->ticketService()
            ->whereIn('service_id', $serviceIds)
            ->delete();
    }

    public function getlinkedDepartment(int $id): Collection
    {
        return $this->model->find($id)
            ->departments
            ->pluck('id');
    }

    public function getlinkedService(int $id): Collection
    {
        return $this->model->find($id)
            ->services
            ->pluck('id');
    }
}
