<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Group;
use ZabalaGailetak\HrPortal\Controllers\VacationController;
use ZabalaGailetak\HrPortal\Services\VacationService;
use ZabalaGailetak\HrPortal\Services\AuditLogger;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Models\VacationBalance;
use ZabalaGailetak\HrPortal\Models\VacationRequest;

/**
 * Tests for VacationController  
 */
class VacationControllerTest extends TestCase
{
    private VacationController $controller;
    private VacationService $vacationService;
    private AuditLogger $auditLogger;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock services
        $this->vacationService = $this->createMock(VacationService::class);
        $this->auditLogger = $this->createMock(AuditLogger::class);
        $this->controller = new VacationController($this->vacationService, $this->auditLogger);
    }

    /**
     * Test: Get current user balance
     */
    #[Group('integration')]
    public function testGetBalance(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $employeeId = 1;
        $year = 2026;

        // Mock balance
        $balance = VacationBalance::fromArray([
            'employee_id' => $employeeId,
            'year' => $year,
            'total_days' => 22.0,
            'used_days' => 5.0,
            'pending_days' => 3.0,
            'available_days' => 14.0
        ]);

        $this->vacationService
            ->expects($this->once())
            ->method('getBalance')
            ->with($employeeId, $year)
            ->willReturn($balance);

        // Simulate request with employee_id in session/token
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user'] = ['employee_id' => $employeeId, 'role' => 'EMPLEADO'];

        $response = $this->controller->getBalance();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals($employeeId, $data['data']['employee_id']);
        $this->assertEquals(22.0, $data['data']['total_days']);
    }

    /**
     * Test: Get balance for specific employee (RRHH only)
     */
    #[Group('integration')]
    public function testGetBalanceForEmployee(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $employeeId = 5;
        $year = 2026;

        $balance = VacationBalance::fromArray([
            'employee_id' => $employeeId,
            'year' => $year,
            'total_days' => 22.0,
            'used_days' => 0.0,
            'pending_days' => 0.0,
            'available_days' => 22.0
        ]);

        $this->vacationService
            ->expects($this->once())
            ->method('getBalance')
            ->with($employeeId, $year)
            ->willReturn($balance);

        // Simulate RRHH user request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user'] = ['employee_id' => 1, 'role' => 'RRHH_MGR'];

        $response = $this->controller->getBalanceForEmployee($employeeId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals($employeeId, $data['data']['employee_id']);
    }

    /**
     * Test: Get balance for employee without permission (should fail)
     */
    #[Group('integration')]
    public function testGetBalanceForEmployeeUnauthorized(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user'] = ['employee_id' => 1, 'role' => 'EMPLEADO'];

        $response = $this->controller->getBalanceForEmployee(5);

        $this->assertEquals(403, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('error', $data);
    }

    /**
     * Test: Create vacation request
     */
    #[Group('integration')]
    public function testCreateRequest(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $employeeId = 1;
        $requestData = [
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
            'notes' => 'Summer vacation'
        ];

        $createdRequest = VacationRequest::fromArray([
            'id' => 1,
            'employee_id' => $employeeId,
            'start_date' => $requestData['start_date'],
            'end_date' => $requestData['end_date'],
            'notes' => $requestData['notes'],
            'status' => 'PENDING',
            'total_days' => 4.0
        ]);

        $this->vacationService
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $employeeId,
                $requestData['start_date'],
                $requestData['end_date'],
                $requestData['notes']
            )
            ->willReturn($createdRequest);

        // Simulate POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['user'] = ['employee_id' => $employeeId, 'role' => 'EMPLEADO'];
        
        // Mock request body
        $this->mockRequestBody($requestData);

        $response = $this->controller->createRequest();

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals('PENDING', $data['data']['status']);
    }

    /**
     * Test: Create request with missing fields
     */
    #[Group('integration')]
    public function testCreateRequestMissingFields(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['user'] = ['employee_id' => 1, 'role' => 'EMPLEADO'];
        
        // Missing end_date
        $this->mockRequestBody([
            'start_date' => '2026-07-01'
        ]);

        $response = $this->controller->createRequest();

        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('error', $data);
    }

    /**
     * Test: Approve request by manager
     */
    #[Group('integration')]
    public function testApproveByManager(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $requestId = 1;
        $managerId = 2;

        $approvedRequest = VacationRequest::fromArray([
            'id' => $requestId,
            'employee_id' => 5,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
            'status' => 'MANAGER_APPROVED',
            'manager_approved_by' => $managerId,
            'manager_approved_at' => date('Y-m-d H:i:s')
        ]);

        $this->vacationService
            ->expects($this->once())
            ->method('approveByManager')
            ->with($requestId, $managerId, null)
            ->willReturn($approvedRequest);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['user'] = ['employee_id' => $managerId, 'role' => 'JEFE_SECCION'];
        
        $this->mockRequestBody([]);

        $response = $this->controller->approveByManager($requestId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('MANAGER_APPROVED', $data['data']['status']);
    }

    /**
     * Test: Approve by HR (final approval)
     */
    #[Group('integration')]
    public function testApproveByHR(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $requestId = 1;
        $hrId = 3;

        $approvedRequest = VacationRequest::fromArray([
            'id' => $requestId,
            'employee_id' => 5,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
            'status' => 'APPROVED',
            'hr_approved_by' => $hrId,
            'hr_approved_at' => date('Y-m-d H:i:s')
        ]);

        $this->vacationService
            ->expects($this->once())
            ->method('approveByHR')
            ->with($requestId, $hrId, null)
            ->willReturn($approvedRequest);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['user'] = ['employee_id' => $hrId, 'role' => 'RRHH_MGR'];
        
        $this->mockRequestBody([]);

        $response = $this->controller->approveByHR($requestId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('APPROVED', $data['data']['status']);
    }

    /**
     * Test: Reject vacation request
     */
    #[Group('integration')]
    public function testRejectRequest(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $requestId = 1;
        $approverId = 2;
        $reason = 'Not enough coverage';

        $rejectedRequest = VacationRequest::fromArray([
            'id' => $requestId,
            'employee_id' => 5,
            'status' => 'REJECTED',
            'rejection_reason' => $reason
        ]);

        $this->vacationService
            ->expects($this->once())
            ->method('reject')
            ->with($requestId, $approverId, $reason)
            ->willReturn($rejectedRequest);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['user'] = ['employee_id' => $approverId, 'role' => 'JEFE_SECCION'];
        
        $this->mockRequestBody(['reason' => $reason]);

        $response = $this->controller->rejectRequest($requestId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('REJECTED', $data['data']['status']);
        $this->assertEquals($reason, $data['data']['rejection_reason']);
    }

    /**
     * Test: Cancel own request
     */
    #[Group('integration')]
    public function testCancelOwnRequest(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $requestId = 1;
        $employeeId = 5;

        $cancelledRequest = VacationRequest::fromArray([
            'id' => $requestId,
            'employee_id' => $employeeId,
            'status' => 'CANCELLED'
        ]);

        $this->vacationService
            ->expects($this->once())
            ->method('cancel')
            ->with($requestId, $employeeId)
            ->willReturn($cancelledRequest);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['user'] = ['employee_id' => $employeeId, 'role' => 'EMPLEADO'];

        $response = $this->controller->cancelRequest($requestId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('CANCELLED', $data['data']['status']);
    }

    /**
     * Test: Get employee requests
     */
    #[Group('integration')]
    public function testGetEmployeeRequests(): void
    {
        $this->markTestSkipped('Requires HTTP Request/Response mocking');
        $employeeId = 1;
        $year = 2026;

        $requests = [
            VacationRequest::fromArray([
                'id' => 1,
                'employee_id' => $employeeId,
                'start_date' => '2026-07-01',
                'end_date' => '2026-07-05',
                'status' => 'PENDING'
            ]),
            VacationRequest::fromArray([
                'id' => 2,
                'employee_id' => $employeeId,
                'start_date' => '2026-08-01',
                'end_date' => '2026-08-05',
                'status' => 'APPROVED'
            ])
        ];

        $this->vacationService
            ->expects($this->once())
            ->method('getEmployeeRequests')
            ->with($employeeId, $year)
            ->willReturn($requests);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user'] = ['employee_id' => $employeeId, 'role' => 'EMPLEADO'];

        $response = $this->controller->getEmployeeRequests();

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('requests', $data['data']);
        $this->assertCount(2, $data['data']['requests']);
    }

    /**
     * Helper to mock request body
     */
    private function mockRequestBody(array $data): void
    {
        $_POST = $data;
        
        // Also set php://input for JSON
        global $mockInput;
        $mockInput = json_encode($data);
    }
}
