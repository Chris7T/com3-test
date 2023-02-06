<?php

namespace Tests\Unit\Actions\Department;

use App\Actions\Department\DepartmentGetAction;
use App\Actions\User\UserCheckIsAdminPermissionAction;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class DepartmentGetActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->departmentRepositoryStub = $this->createMock(DepartmentRepositoryInterface::class);
        $this->userCheckAdminPermissionActionStub = $this->createMock(UserCheckIsAdminPermissionAction::class);
    }

    public function test_expected_not_found_exception_when_repository_return_null()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Department not found');
        $id = 1;

        $this->departmentRepositoryStub
            ->expects($this->once())
            ->method('getDepartmentById')
            ->with($id)
            ->willReturn(null);

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        $department = new DepartmentGetAction(
            departmentRepository: $this->departmentRepositoryStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $department->execute($id);
    }

    public function test_expected_not_found_exception_when_cache_return_null()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Department not found');
        $id = 1;

        Cache::shouldReceive('remember')
            ->once()
            ->with("department-{$id}", config('cache.one_day'), \Closure::class)
            ->andReturn(null);

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        $department = new DepartmentGetAction(
            departmentRepository: $this->departmentRepositoryStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $department->execute($id);
    }

    public function test_expected_Department_when_data_exists_in_cache()
    {
        $id = 1;
        $departmentExpexted = new Department();

        Cache::shouldReceive('remember')
            ->once()
            ->with("department-{$id}", config('cache.one_day'), \Closure::class)
            ->andReturn($departmentExpexted);

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        $department = new DepartmentGetAction(
            departmentRepository: $this->departmentRepositoryStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $return = $department->execute($id);

        $this->assertEquals($departmentExpexted, $return);
    }

    public function test_expected_Department_when_data_exists_in_database()
    {
        $id = 1;
        $departmentExpexted = new Department();

        $this->departmentRepositoryStub
            ->expects($this->once())
            ->method('getDepartmentById')
            ->with($id)
            ->willReturn($departmentExpexted);

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        $department = new DepartmentGetAction(
            departmentRepository: $this->departmentRepositoryStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $return = $department->execute($id);

        $this->assertEquals($departmentExpexted, $return);
    }
}
