<?php

namespace Tests\Unit\Actions\Service;

use App\Actions\Service\ServiceGetAction;
use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class ServiceGetActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->serviceRepositoryStub = $this->createMock(ServiceRepositoryInterface::class);
    }

    public function test_expected_not_found_exception_when_repository_return_null()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Service not found');
        $id = 1;
        $this->serviceRepositoryStub
            ->expects($this->once())
            ->method('getServiceById')
            ->with($id)
            ->willReturn(null);
        $service = new ServiceGetAction(
            serviceRepository: $this->serviceRepositoryStub,
        );

        $service->execute($id);
    }

    public function test_expected_not_found_exception_when_cache_return_null()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Service not found');
        $id = 1;

        Cache::shouldReceive('remember')
            ->once()
            ->with("service-{$id}", config('cache.one_day'), \Closure::class)
            ->andReturn(null);
        $service = new ServiceGetAction(
            serviceRepository: $this->serviceRepositoryStub,
        );

        $service->execute($id);
    }

    public function test_expected_service_when_data_exists_in_cache()
    {
        $id = 1;
        $serviceExpexted = new Service();

        Cache::shouldReceive('remember')
            ->once()
            ->with("service-{$id}", config('cache.one_day'), \Closure::class)
            ->andReturn($serviceExpexted);
        $service = new ServiceGetAction(
            serviceRepository: $this->serviceRepositoryStub,
        );

        $return = $service->execute($id);

        $this->assertEquals($serviceExpexted, $return);
    }

    public function test_expected_service_when_data_exists_in_database()
    {
        $id = 1;
        $serviceExpexted = new Service();

        $this->serviceRepositoryStub
            ->expects($this->once())
            ->method('getServiceById')
            ->with($id)
            ->willReturn($serviceExpexted);

        $service = new ServiceGetAction(
            serviceRepository: $this->serviceRepositoryStub,
        );

        $return = $service->execute($id);

        $this->assertEquals($serviceExpexted, $return);
    }
}
