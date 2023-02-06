<?php

namespace Tests\Unit\Actions\Department;

use App\Actions\Department\DepartmentCreateAction;
use App\Actions\User\UserCheckAdminPermissionAction;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DepartmentCreateActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->departmentRepositoryStub = $this->createMock(DepartmentRepositoryInterface::class);
        $this->userCheckAdminPermissionActionStub = $this->createMock(UserCheckAdminPermissionAction::class);
    }

    public function test_expected_void_when_data_exists()
    {
        $id = 1;
        $description = 'description teste';
        $departmentExpected = new Department();
        $departmentExpected->description = $description;
        $departmentExpected->id = $id;

        $this->departmentRepositoryStub
            ->expects($this->once())
            ->method('createDepartment')
            ->with($description)
            ->willReturn($departmentExpected);

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        Cache::shouldReceive('put')
            ->once()
            ->with("department-{$id}", $departmentExpected, config('cache.time.one_month'));

        $department = new DepartmentCreateAction(
            departmentRepository: $this->departmentRepositoryStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $return = $department->execute($description);

        $this->assertEquals($return, $departmentExpected);
    }
}
