<?php

namespace Tests\Unit\Actions\Service;

use App\Actions\Service\ServiceCreateAction;
use App\Actions\User\UserCheckAdminPermissionAction;
use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ServiceCreateActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->serviceRepositoryStub = $this->createMock(ServiceRepositoryInterface::class);
        $this->userCheckAdminPermissionActionStub = $this->createMock(UserCheckAdminPermissionAction::class);
    }

    public function test_expected_void_when_data_exists()
    {
        $id = 1;
        $description = 'description teste';
        $serviceExpected = new Service();
        $serviceExpected->description = $description;
        $serviceExpected->id = $id;

        $this->serviceRepositoryStub
            ->expects($this->once())
            ->method('createService')
            ->with($description)
            ->willReturn($serviceExpected);

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        Cache::shouldReceive('put')
            ->once()
            ->with("service-{$id}", $serviceExpected, config('cache.time.one_month'));
        $service = new ServiceCreateAction(
            serviceRepository: $this->serviceRepositoryStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $return = $service->execute($description);

        $this->assertEquals($return, $serviceExpected);
    }
}
