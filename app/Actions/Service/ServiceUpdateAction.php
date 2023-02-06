<?php

namespace App\Actions\Service;

use App\Actions\User\UserCheckAdminPermissionAction;
use App\Repositories\Service\ServiceRepositoryInterface;

class ServiceUpdateAction
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly ServiceGetAction $serviceGetAction,
        private readonly UserCheckAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(int $id, string $description): void
    {
        $this->userCheckAdminPermissionAction->execute();
        $this->serviceGetAction->execute($id);
        $this->serviceRepository->update($id, $description);
    }
}
