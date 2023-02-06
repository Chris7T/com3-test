<?php

namespace App\Actions\Service;

use App\Actions\User\UserCheckAdminPermissionAction;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ServiceDeleteAction
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly ServiceGetAction $serviceGetAction,
        private readonly UserCheckAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(int $id): void
    {
        $this->userCheckAdminPermissionAction->execute();
        $this->serviceGetAction->execute($id);
        $this->serviceRepository->deleteServiceById($id);
        Cache::forget("service-{$id}");
        Cache::forget('service-list');
    }
}
