<?php

namespace App\Repositories\Service;

use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function __construct(
        private readonly Service $model
    ) {
    }

    public function getServiceById(int $id): ?Service
    {
        return $this->model->find($id);
    }

    public function createService(string $description): Service
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

    public function deleteServiceById(int $id): void
    {
        $this->model->find($id)->delete();
    }
}
