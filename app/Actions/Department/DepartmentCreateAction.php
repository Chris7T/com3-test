<?php

namespace App\Actions\Department;

use App\Actions\User\UserCheckIsAdminPermissionAction;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class DepartmentCreateAction
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly UserCheckIsAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(string $description): Department
    {
        $this->userCheckAdminPermissionAction->execute();
        $department = $this->departmentRepository->createDepartment($description);
        Cache::put("department-{$department->getKey()}", $department, config('cache.time.one_month'));

        return $department;
    }
}
