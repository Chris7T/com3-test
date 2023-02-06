<?php

namespace App\Actions\Service;

use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceGetAction
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function execute(int $id): Service
    {
        $service = Cache::remember(
            "service-{$id}",
            config('cache.one_day'),
            fn () => $this->serviceRepository->getServiceById($id)
        );

        if (is_null($service)) {
            throw new NotFoundHttpException('Service not found');
        }

        return $service;
    }
}
