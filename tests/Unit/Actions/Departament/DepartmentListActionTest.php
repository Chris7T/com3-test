<?php

namespace Tests\Unit\Actions\Department;

use App\Actions\Department\DepartmentListAction;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DepartmentListActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->departmentRepositoryStub = $this->createMock(DepartmentRepositoryInterface::class);
    }

    public function test_expected_Departments_list_when_data_exists_in_cache()
    {
        $returnExpexted = new LengthAwarePaginator([new Department], 1, 1);

        Cache::shouldReceive('remember')
            ->once()
            ->with('department-list', config('cache.one_day'), \Closure::class)
            ->andReturn($returnExpexted);
        $department = new DepartmentListAction(
            departmentRepository: $this->departmentRepositoryStub,
        );

        $return = $department->execute();

        $this->assertEquals($returnExpexted, $return);
    }

    public function test_expected_Departments_list_when_data_exists_in_database()
    {
        $returnExpexted = new LengthAwarePaginator([new Department], 1, 1);

        $this->departmentRepositoryStub
            ->expects($this->once())
            ->method('list')
            ->willReturn($returnExpexted);
        $department = new DepartmentListAction(
            departmentRepository: $this->departmentRepositoryStub,
        );

        $return = $department->execute();

        $this->assertEquals($returnExpexted, $return);
    }
}
