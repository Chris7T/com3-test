<?php

namespace App\Actions\Service;

use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ServiceListAction
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function execute(): LengthAwarePaginator
    {
        return Cache::remember(
            'service-list',
            config('cache.one_day'),
            function () {
                return $this->serviceRepository->list();
            }
        );
    }
}
