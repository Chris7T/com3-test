<?php

namespace App\Actions\Service;

use App\Repositories\Service\ServiceRepositoryInterface;

class ServiceUpdateAction
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly ServiceGetAction $serviceGetAction,
    ) {
    }

    public function execute(int $id, string $description): void
    {
        $this->serviceGetAction->execute($id);

        $this->serviceRepository->update($id, $description);
    }
}
