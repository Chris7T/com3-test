<?php

namespace Tests\Unit\Actions\Department;

use App\Actions\Department\DepartmentGetAction;
use App\Actions\Department\DepartmentUpdateAction;
use App\Actions\User\UserCheckAdminPermissionAction;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class DepartmentUpdateActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->departmentRepositoryStub = $this->createMock(DepartmentRepositoryInterface::class);
        $this->departmentGetActionStub = $this->createMock(DepartmentGetAction::class);
        $this->userCheckAdminPermissionActionStub = $this->createMock(UserCheckAdminPermissionAction::class);
    }

    public function test_expected_not_found_http_exception_when_data_does_not_exists()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Department not found');

        $id = 1;
        $description = 'description teste';
        $departmentExpected = new Department();
        $departmentExpected->description = $description;
        $departmentExpected->id = $id;

        $this->departmentGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id)
            ->willThrowException(new NotFoundHttpException('Department not found'));

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        $department = new DepartmentUpdateAction(
            departmentRepository: $this->departmentRepositoryStub,
            departmentGetAction: $this->departmentGetActionStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $department->execute($id, $description);
    }

    public function test_expected_void_when_data_exists()
    {
        $id = 1;
        $description = 'description teste';
        $departmentExpected = new Department();
        $departmentExpected->description = $description;
        $departmentExpected->id = $id;

        $this->departmentGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id);

        $this->departmentRepositoryStub
            ->expects($this->once())
            ->method('update')
            ->with($id, $description);

        $this->userCheckAdminPermissionActionStub
            ->expects($this->once())
            ->method('execute');

        $department = new DepartmentUpdateAction(
            departmentRepository: $this->departmentRepositoryStub,
            departmentGetAction: $this->departmentGetActionStub,
            userCheckAdminPermissionAction: $this->userCheckAdminPermissionActionStub
        );

        $department->execute($id, $description);
    }
}
