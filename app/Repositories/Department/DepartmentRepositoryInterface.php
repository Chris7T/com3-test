<?php

namespace App\Repositories\Department;

use App\Models\Department;
use Illuminate\Pagination\LengthAwarePaginator;

interface DepartmentRepositoryInterface
{
    public function getDepartmentById(int $id): ?Department;
    public function createDepartment(string $description): Department;
    public function list(): LengthAwarePaginator;
    public function update(int $id, string $description): void;
    public function deleteDepartmentById(int $id): void;
}
