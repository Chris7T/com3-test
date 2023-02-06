<?php

namespace Tests\Unit\Actions\Department;

use App\Actions\Department\DepartmentDeleteAction;
use App\Actions\Department\DepartmentGetAction;
use App\Repositories\Department\DepartmentRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class DepartmentDeleteActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->departmentRepositoryStub = $this->createMock(DepartmentRepositoryInterface::class);
        $this->departmentGetActionStub = $this->createMock(DepartmentGetAction::class);
    }

    public function test_expected_not_found_exception_when_data_does_not_exists()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Department not found');
        $id = 1;
        $this->departmentGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id)
            ->willThrowException(new NotFoundHttpException('Department not found'));
        $department = new DepartmentDeleteAction(
            departmentRepository: $this->departmentRepositoryStub,
            departmentGetAction: $this->departmentGetActionStub
        );

        $department->execute($id);
    }

    public function test_expected_void_when_data_exists()
    {
        $id = 1;

        $this->departmentGetActionStub
            ->expects($this->once())
            ->method('execute')
            ->with($id);

        $this->departmentRepositoryStub
            ->expects($this->once())
            ->method('deleteDepartmentById')
            ->with($id);

        $department = new DepartmentDeleteAction(
            departmentRepository: $this->departmentRepositoryStub,
            departmentGetAction: $this->departmentGetActionStub
        );

        $department->execute($id);
    }
}
