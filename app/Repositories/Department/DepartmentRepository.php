<?php

namespace App\Repositories\Department;

use App\Models\Department;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function __construct(
        private readonly Department $model
    ) {
    }

    public function getDepartmentById(int $id): ?Department
    {
        return $this->model->find($id);
    }

    public function createDepartment(string $description): Department
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

    public function deleteDepartmentById(int $id): void
    {
        $this->model->find($id)->delete();
    }
}
