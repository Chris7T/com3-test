<?php

namespace Tests\Unit\Actions\Service;

use App\Actions\Service\ServiceListAction;
use App\Models\Service;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ServiceListActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->serviceRepositoryStub = $this->createMock(ServiceRepositoryInterface::class);
    }

    public function test_expected_services_list_when_data_exists_in_cache()
    {
        $returnExpexted = new LengthAwarePaginator([new Service], 1, 1);

        Cache::shouldReceive('remember')
            ->once()
            ->with('service-list', config('cache.one_day'), \Closure::class)
            ->andReturn($returnExpexted);
        $service = new ServiceListAction(
            serviceRepository: $this->serviceRepositoryStub,
        );

        $return = $service->execute();

        $this->assertEquals($returnExpexted, $return);
    }

    public function test_expected_services_list_when_data_exists_in_database()
    {
        $returnExpexted = new LengthAwarePaginator([new Service], 1, 1);

        $this->serviceRepositoryStub
            ->expects($this->once())
            ->method('list')
            ->willReturn($returnExpexted);
        $service = new ServiceListAction(
            serviceRepository: $this->serviceRepositoryStub,
        );

        $return = $service->execute();

        $this->assertEquals($returnExpexted, $return);
    }
}
