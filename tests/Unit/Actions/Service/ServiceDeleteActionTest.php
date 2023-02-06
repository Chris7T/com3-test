<?php

namespace Tests\Unit\Actions\Service;

use App\Actions\Service\ServiceDeleteAction;
use App\Actions\Service\ServiceGetAction;
use App\Repositories\Service\ServiceRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class ServiceDeleteActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->serviceRepositoryStub = $this->createMock(ServiceRepositoryInterface::class);
        $this->serviceGetActionStub = $this->createMock(ServiceGetAction::class);
    }

    public function test_expected_not_found_exception_when_data_does_not_exists()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Service not found');
        $id = 1;
        $this->serviceGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id)
            ->willThrowException(new NotFoundHttpException('Service not found'));
        $service = new ServiceDeleteAction(
            serviceRepository: $this->serviceRepositoryStub,
            serviceGetAction: $this->serviceGetActionStub
        );

        $service->execute($id);
    }

    public function test_expected_void_when_data_exists()
    {
        $id = 1;

        $this->serviceGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id);

        $this->serviceRepositoryStub
            ->expects($this->once())
            ->method('deleteServiceById')
            ->with($id);

        $service = new ServiceDeleteAction(
            serviceRepository: $this->serviceRepositoryStub,
            serviceGetAction: $this->serviceGetActionStub
        );

        $service->execute($id);
    }
}
