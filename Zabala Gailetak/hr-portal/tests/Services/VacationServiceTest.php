<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Tests\Services;

use PHPUnit\Framework\TestCase;
use ZabalaGailetak\HrPortal\Services\VacationService;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Models\VacationRequest;
use ZabalaGailetak\HrPortal\Models\VacationBalance;
use PDO;
use PDOStatement;

class VacationServiceTest extends TestCase
{
    private VacationService $service;
    /** @var Database&\PHPUnit\Framework\MockObject\MockObject */
    private Database $mockDb;
    /** @var PDO&\PHPUnit\Framework\MockObject\MockObject */
    private PDO $mockPdo;

    protected function setUp(): void
    {
        $this->mockDb = $this->createMock(Database::class);
        $this->mockPdo = $this->createMock(PDO::class);

        $this->mockDb->method('getConnection')->willReturn($this->mockPdo);

        $this->service = new VacationService($this->mockDb);
    }

    public function testInitializeBalanceCreatesNewRecord(): void
    {
        $employeeId = 'emp-uuid-1';
        $year = 2026;
        $totalDays = 22.0;

        // Mock getBalance to return null first (not found)
        $mockStmtGet = $this->createMock(PDOStatement::class);
        $mockStmtGet->method('fetch')->willReturn(false); // First call returns false

        // Mock insert
        $mockStmtInsert = $this->createMock(PDOStatement::class);
        $mockStmtInsert->expects($this->once())
            ->method('execute')
            ->with([
                'employee_id' => $employeeId,
                'year' => $year,
                'total_days' => $totalDays
            ]);

        // Mock getBalance second call (after insert) to return the new record
        $mockStmtGet2 = $this->createMock(PDOStatement::class);
        $mockStmtGet2->method('fetch')->willReturn([
            'id' => 'balance-uuid-1',
            'employee_id' => $employeeId,
            'year' => $year,
            'total_days' => $totalDays,
            'used_days' => 0.0,
            'pending_days' => 0.0,
            'available_days' => $totalDays
        ]);

        $this->mockPdo->expects($this->exactly(3))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($mockStmtGet, $mockStmtInsert, $mockStmtGet2);

        $balance = $this->service->initializeBalance($employeeId, $year, $totalDays);

        $this->assertInstanceOf(VacationBalance::class, $balance);
        $this->assertEquals($employeeId, $balance->employeeId);
        $this->assertEquals($totalDays, $balance->totalDays);
    }

    public function testCreateRequestSuccess(): void
    {
        $employeeId = 'emp-uuid-1';
        $startDate = '2026-06-01'; // Monday
        $endDate = '2026-06-05';   // Friday (5 days)
        $year = 2026;

        // Mock public holidays (empty)
        $mockStmtHolidays = $this->createMock(PDOStatement::class);
        $mockStmtHolidays->method('fetch')->willReturn(false); // No holidays

        // Mock getBalance (enough days)
        $mockStmtBalance = $this->createMock(PDOStatement::class);
        $mockStmtBalance->method('fetch')->willReturn([
            'id' => 'balance-uuid-1',
            'employee_id' => $employeeId,
            'year' => $year,
            'total_days' => 22.0,
            'used_days' => 0.0,
            'pending_days' => 0.0,
            'available_days' => 22.0
        ]);

        // Mock overlap check (no overlapping requests)
        $mockStmtOverlap = $this->createMock(PDOStatement::class);
        $mockStmtOverlap->expects($this->once())
            ->method('execute');
        $mockStmtOverlap->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(0); // No overlapping requests

        // Mock insert request
        $mockStmtInsert = $this->createMock(PDOStatement::class);
        $mockStmtInsert->expects($this->once())
            ->method('execute')
            ->willReturn(true); // Execution successful
        $mockStmtInsert->expects($this->once())
            ->method('fetchColumn')
            ->willReturn('request-uuid-1');

        // Mock getRequest (to return the created request)
        $mockStmtGetRequest = $this->createMock(PDOStatement::class);
        $mockStmtGetRequest->method('fetch')->willReturn([
            'id' => 'request-uuid-1',
            'employee_id' => $employeeId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => 5.0,
            'status' => 'PENDING',
            'employee_first_name' => 'John',
            'employee_last_name' => 'Doe',
            'employee_email' => 'john@test.com',
            'employee_department' => 'IT'
        ]);

        // Sequence of prepare calls:
        // 1. getPublicHolidays
        // 2. getBalance
        // 3. overlap check
        // 4. insert request
        // 5. getRequest
        $this->mockPdo->expects($this->exactly(5))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls(
                $mockStmtHolidays,
                $mockStmtBalance,
                $mockStmtOverlap,
                $mockStmtInsert,
                $mockStmtGetRequest
            );

        $request = $this->service->createRequest($employeeId, $startDate, $endDate, 'Vacation');

        $this->assertInstanceOf(VacationRequest::class, $request);
        $this->assertEquals('request-uuid-1', $request->id);
        $this->assertEquals(5.0, $request->totalDays);
    }
}
