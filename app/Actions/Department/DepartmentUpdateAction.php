<?php

namespace App\Actions\Department;

use App\Actions\User\UserCheckIsAdminPermissionAction;
use App\Repositories\Department\DepartmentRepositoryInterface;

class DepartmentUpdateAction
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly DepartmentGetAction $departmentGetAction,
        private readonly UserCheckIsAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(int $id, string $description): void
    {
        $this->userCheckAdminPermissionAction->execute();
        $this->departmentGetAction->execute($id);
        $this->departmentRepository->update($id, $description);
    }
}
