<?php

namespace App\Actions\Service;

use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ServiceCreateAction
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function execute(string $description): Service
    {
        $service = $this->serviceRepository->createService($description);
        Cache::put("service-{$service->getKey()}", $service, config('cache.time.one_month'));

        return $service;
    }
}
