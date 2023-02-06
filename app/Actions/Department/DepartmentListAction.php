<?php

namespace App\Actions\Department;

use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class DepartmentListAction
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository
    ) {
    }

    public function execute(): LengthAwarePaginator
    {
        return Cache::remember(
            'department-list',
            config('cache.one_day'),
            fn () => $this->departmentRepository->list()
        );
    }
}
