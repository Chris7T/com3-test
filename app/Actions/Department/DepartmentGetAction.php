<?php

namespace App\Actions\Department;

use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentGetAction
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository
    ) {
    }

    public function execute(int $id): Department
    {
        $department = Cache::remember(
            "departament-{$id}",
            config('cache.one_day'),
            fn () => $this->departmentRepository->getDepartmentById($id)
        );

        if (is_null($department)) {
            throw new NotFoundHttpException('Department not found');
        }

        return $department;
    }
}
