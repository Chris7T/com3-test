<?php

namespace App\Actions\Service;

use App\Actions\User\UserCheckAdminPermissionAction;
use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ServiceCreateAction
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly UserCheckAdminPermissionAction $userCheckAdminPermissionAction
    ) {
    }

    public function execute(string $description): Service
    {
        $this->userCheckAdminPermissionAction->execute();
        $service = $this->serviceRepository->createService($description);
        Cache::put("service-{$service->getKey()}", $service, config('cache.time.one_month'));

        return $service;
    }
}
