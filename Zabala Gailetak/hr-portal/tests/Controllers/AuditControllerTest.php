<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Tests\Controllers;

use PHPUnit\Framework\TestCase;
use ZabalaGailetak\HrPortal\Controllers\AuditController;
use ZabalaGailetak\HrPortal\Auth\AccessControl;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Services\AuditLogger;

class AuditControllerTest extends TestCase
{
    private AuditController $controller;
    /** @var AuditLogger&\PHPUnit\Framework\MockObject\MockObject */
    private AuditLogger $mockLogger;
    /** @var AccessControl&\PHPUnit\Framework\MockObject\MockObject */
    private AccessControl $mockAccessControl;

    protected function setUp(): void
    {
        $this->mockLogger = $this->createMock(AuditLogger::class);
        $this->mockAccessControl = $this->createMock(AccessControl::class);
        $this->controller = new AuditController($this->mockLogger, $this->mockAccessControl);
    }

    private function createAuthenticatedRequest(
        string $role = 'admin',
        string $userId = 'test-user-id',
        array $query = []
    ): Request {
        $request = $this->createMock(Request::class);

        $request->method('getAttribute')
            ->willReturnCallback(function ($key) use ($role, $userId) {
                return match ($key) {
                    'user_role' => $role,
                    'user_id' => $userId,
                    default => null
                };
            });

        $request->method('getQuery')
            ->willReturnCallback(function ($key) use ($query) {
                return $query[$key] ?? null;
            });

        return $request;
    }

    public function testGetEmployeeHistorySuccess(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id');

        $this->mockAccessControl->method('hasPermission')
            ->with('admin', 'employees.view')
            ->willReturn(true);

        $mockHistory = [
            [
                'id' => 'log-1',
                'action' => 'update',
                'user_email' => 'admin@example.com',
                'changed_fields' => ['salary', 'position'],
                'created_at' => '2024-01-15 10:00:00'
            ],
            [
                'id' => 'log-2',
                'action' => 'create',
                'user_email' => 'admin@example.com',
                'changed_fields' => ['first_name', 'last_name'],
                'created_at' => '2024-01-14 09:00:00'
            ]
        ];

        $this->mockLogger->expects($this->once())
            ->method('getEntityHistory')
            ->with('employee', 'emp-1', 50)
            ->willReturn($mockHistory);

        $response = $this->controller->getEmployeeHistory($request, 'emp-1');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertEquals('emp-1', $data['employee_id']);
        $this->assertCount(2, $data['history']);
        $this->assertEquals(2, $data['count']);
    }

    public function testGetEmployeeHistoryWithLimit(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id', ['limit' => '10']);

        $this->mockAccessControl->method('hasPermission')
            ->willReturn(true);

        $this->mockLogger->expects($this->once())
            ->method('getEntityHistory')
            ->with('employee', 'emp-1', 10)
            ->willReturn([]);

        $response = $this->controller->getEmployeeHistory($request, 'emp-1');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetEmployeeHistoryMaxLimit(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id', ['limit' => '500']);

        $this->mockAccessControl->method('hasPermission')
            ->willReturn(true);

        // Debe limitar a 200 máximo
        $this->mockLogger->expects($this->once())
            ->method('getEntityHistory')
            ->with('employee', 'emp-1', 200)
            ->willReturn([]);

        $this->controller->getEmployeeHistory($request, 'emp-1');
    }

    public function testGetEmployeeHistoryUnauthorized(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getAttribute')->willReturn(null);

        $response = $this->controller->getEmployeeHistory($request, 'emp-1');

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testGetEmployeeHistoryForbidden(): void
    {
        $request = $this->createAuthenticatedRequest('employee', 'emp-id');

        $this->mockAccessControl->method('hasPermission')
            ->with('employee', 'employees.view')
            ->willReturn(false);

        $response = $this->controller->getEmployeeHistory($request, 'emp-1');

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testGetUserActivitySuccess(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id');

        $mockActivity = [
            [
                'id' => 'log-1',
                'entity_type' => 'employee',
                'entity_id' => 'emp-1',
                'action' => 'update',
                'created_at' => '2024-01-15 10:00:00'
            ]
        ];

        $this->mockLogger->expects($this->once())
            ->method('getUserActivity')
            ->with('user-1', null, 50)
            ->willReturn($mockActivity);

        $response = $this->controller->getUserActivity($request, 'user-1');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertEquals('user-1', $data['user_id']);
        $this->assertCount(1, $data['activity']);
    }

    public function testGetUserActivityWithEntityTypeFilter(): void
    {
        $request = $this->createAuthenticatedRequest('admin', 'admin-id', ['entity_type' => 'employee']);

        $this->mockLogger->expects($this->once())
            ->method('getUserActivity')
            ->with('user-1', 'employee', 50)
            ->willReturn([]);

        $response = $this->controller->getUserActivity($request, 'user-1');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetUserActivityOwnActivity(): void
    {
        // Un usuario puede ver su propia actividad
        $request = $this->createAuthenticatedRequest('employee', 'emp-1');

        $this->mockLogger->method('getUserActivity')
            ->willReturn([]);

        $response = $this->controller->getUserActivity($request, 'emp-1');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetUserActivityForbiddenForNonAdmin(): void
    {
        // Un employee no puede ver actividad de otros
        $request = $this->createAuthenticatedRequest('employee', 'emp-1');

        $response = $this->controller->getUserActivity($request, 'user-2');

        $this->assertEquals(403, $response->getStatusCode());
    }
}
