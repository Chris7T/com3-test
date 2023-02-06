<?php

namespace App\Actions\Department;

use App\Actions\User\UserCheckIsAdminPermissionAction;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class DepartmentDeleteAction
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly DepartmentGetAction $departmentGetAction,
        private readonly UserCheckIsAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(int $id): void
    {
        $this->userCheckAdminPermissionAction->execute();
        $this->departmentGetAction->execute($id);
        $this->departmentRepository->deleteDepartmentById($id);
        Cache::forget("departament-{$id}");
        Cache::forget('departament-list');
    }
}
