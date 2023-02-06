<?php

namespace App\Repositories\Ticket;

use App\Enum\Ticket\TicketStatusEnum;
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

    public function createTicket(string $description, int $userId): Ticket
    {
        return $this->model->create(
            [
                'description' => $description,
                'user_id' => $userId,
                'ticket_status' => TicketStatusEnum::PENDING->value
            ]
        );
    }
    public function list(?int $userId): LengthAwarePaginator
    {
        return $this->model
            ->when(!is_null($userId), function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->paginate();
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

    public function setStatusConcluded(int $id): void
    {
        $this->model->find($id)->update(['ticket_status' => TicketStatusEnum::CONCLUDED->value]);
    }

    public function setStatusProgress(int $id): void
    {
        $this->model->find($id)->update(['ticket_status' => TicketStatusEnum::PROGRESS->value]);
    }
}
