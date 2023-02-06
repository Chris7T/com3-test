<?php

namespace App\Repositories\Service;

use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceRepositoryInterface
{
    public function getServiceById(int $id): ?Service;
    public function createService(string $description): Service;
    public function list(): LengthAwarePaginator;
    public function update(int $id, string $description): void;
    public function deleteServiceById(int $id): void;
}
