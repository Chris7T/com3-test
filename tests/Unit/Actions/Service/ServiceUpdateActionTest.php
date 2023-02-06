<?php

namespace Tests\Unit\Actions\Service;

use App\Actions\Service\ServiceGetAction;
use App\Actions\Service\ServiceUpdateAction;
use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class ServiceUpdateActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->serviceRepositoryStub = $this->createMock(ServiceRepositoryInterface::class);
        $this->serviceGetActionStub = $this->createMock(ServiceGetAction::class);
    }

    public function test_expected_not_found_http_exception_when_data_does_not_exists()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Service not found');

        $id = 1;
        $description = 'description teste';
        $serviceExpected = new Service();
        $serviceExpected->description = $description;
        $serviceExpected->id = $id;

        $this->serviceGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id)
            ->willThrowException(new NotFoundHttpException('Service not found'));

        $service = new ServiceUpdateAction(
            serviceRepository: $this->serviceRepositoryStub,
            serviceGetAction: $this->serviceGetActionStub
        );

        $service->execute($id, $description);
    }

    public function test_expected_void_when_data_exists()
    {
        $id = 1;
        $description = 'description teste';
        $serviceExpected = new Service();
        $serviceExpected->description = $description;
        $serviceExpected->id = $id;

        $this->serviceGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id);

        $this->serviceRepositoryStub
            ->expects($this->once())
            ->method('update')
            ->with($id, $description);

        $service = new ServiceUpdateAction(
            serviceRepository: $this->serviceRepositoryStub,
            serviceGetAction: $this->serviceGetActionStub
        );

        $service->execute($id, $description);
    }
}
