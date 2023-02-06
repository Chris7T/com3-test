<?php

namespace App\Actions\Department;

use App\Repositories\Department\DepartmentRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentUpdateAction
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly DepartmentGetAction $departmentGetAction,
    ) {
    }

    public function execute(int $id, string $description): void
    {
        $this->departmentGetAction->execute($id);

        $this->departmentRepository->update($id, $description);
    }
}
