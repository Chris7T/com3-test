<?php

namespace App\Actions\Department;

use App\Actions\User\UserCheckAdminPermissionAction;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentGetAction
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly UserCheckAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(int $id): Department
    {
        $this->userCheckAdminPermissionAction->execute();
        $department = Cache::remember(
            "department-{$id}",
            config('cache.one_day'),
            fn () => $this->departmentRepository->getDepartmentById($id)
        );

        if (is_null($department)) {
            throw new NotFoundHttpException('Department not found');
        }

        return $department;
    }
}
