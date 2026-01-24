<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Group;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Services\VacationService;
use ZabalaGailetak\HrPortal\Services\AuditLogger;

/**
 * Integration tests for Vacation workflow
 * Tests the complete flow: Create → Manager Approval → HR Approval
 */
class VacationWorkflowTest extends TestCase
{
    private Database $db;
    private VacationService $vacationService;
    private int $testEmployeeId;
    private int $testManagerId;
    private int $testHrId;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock database for integration tests
        $this->db = $this->createMock(Database::class);
        $auditLogger = $this->createMock(AuditLogger::class);
        $this->vacationService = new VacationService($this->db, $auditLogger);

        // Set test user IDs
        $this->testEmployeeId = 100;
        $this->testManagerId = 101;
        $this->testHrId = 102;
    }

    protected function tearDown(): void
    {
        // No cleanup needed with mocks
        parent::tearDown();
    }

    /**
     * Test: Complete vacation approval workflow
     * Employee creates request → Manager approves → HR approves
     */
    #[Group('integration')]
    public function testCompleteApprovalWorkflow(): void
    {
        $this->markTestSkipped('Requires full database integration for end-to-end testing');
        // Step 1: Initialize employee balance
        $balance = $this->vacationService->initializeBalance($this->testEmployeeId, 2026, 22.0);
        $this->assertEquals(22.0, $balance->totalDays);
        $this->assertEquals(22.0, $balance->availableDays);

        // Step 2: Employee creates vacation request
        $request = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-07-01',
            '2026-07-10',
            'Summer vacation'
        );

        $this->assertEquals('PENDING', $request->status);
        $this->assertGreaterThan(0, $request->totalDays);

        // Check balance - days should be pending
        $balanceAfterRequest = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $this->assertEquals($request->totalDays, $balanceAfterRequest->pendingDays);
        $this->assertEquals(0, $balanceAfterRequest->usedDays);
        $this->assertEquals(22.0 - $request->totalDays, $balanceAfterRequest->availableDays);

        // Step 3: Manager approves request
        $managerApproved = $this->vacationService->approveByManager(
            $request->id,
            $this->testManagerId,
            'Approved - good timing'
        );

        $this->assertEquals('MANAGER_APPROVED', $managerApproved->status);
        $this->assertEquals($this->testManagerId, $managerApproved->managerApprovedBy);
        $this->assertNotNull($managerApproved->managerApprovedAt);

        // Balance should still have pending days (not yet final approval)
        $balanceAfterManager = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $this->assertEquals($request->totalDays, $balanceAfterManager->pendingDays);

        // Step 4: HR final approval
        $hrApproved = $this->vacationService->approveByHR(
            $request->id,
            $this->testHrId,
            'Final approval - all OK'
        );

        $this->assertEquals('APPROVED', $hrApproved->status);
        $this->assertEquals($this->testHrId, $hrApproved->hrApprovedBy);
        $this->assertNotNull($hrApproved->hrApprovedAt);

        // Final check: Balance should reflect used days, no pending
        $finalBalance = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $this->assertEquals($request->totalDays, $finalBalance->usedDays);
        $this->assertEquals(0, $finalBalance->pendingDays);
        $this->assertEquals(22.0 - $request->totalDays, $finalBalance->availableDays);
    }

    /**
     * Test: Manager rejection workflow
     */
    #[Group('integration')]
    public function testManagerRejectionWorkflow(): void
    {
        $this->markTestSkipped('Requires full database integration for end-to-end testing');
        // Initialize and create request
        $this->vacationService->initializeBalance($this->testEmployeeId, 2026, 22.0);
        $request = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-12-20',
            '2026-12-31',
            'End of year vacation'
        );

        $balanceAfterRequest = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $pendingDays = $balanceAfterRequest->pendingDays;
        $this->assertGreaterThan(0, $pendingDays);

        // Manager rejects
        $rejected = $this->vacationService->reject(
            $request->id,
            $this->testManagerId,
            'Too many people on vacation at that time'
        );

        $this->assertEquals('REJECTED', $rejected->status);
        $this->assertEquals('Too many people on vacation at that time', $rejected->rejectionReason);

        // Balance should release pending days
        $balanceAfterRejection = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $this->assertEquals(0, $balanceAfterRejection->pendingDays);
        $this->assertEquals(0, $balanceAfterRejection->usedDays);
        $this->assertEquals(22.0, $balanceAfterRejection->availableDays);
    }

    /**
     * Test: Employee cancellation workflow
     */
    #[Group('integration')]
    public function testEmployeeCancellationWorkflow(): void
    {
        $this->markTestSkipped('Requires full database integration for end-to-end testing');
        // Initialize and create request
        $this->vacationService->initializeBalance($this->testEmployeeId, 2026, 22.0);
        $request = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-08-01',
            '2026-08-05',
            'August vacation'
        );

        $this->assertEquals('PENDING', $request->status);

        // Employee cancels before approval
        $cancelled = $this->vacationService->cancel($request->id, $this->testEmployeeId);

        $this->assertEquals('CANCELLED', $cancelled->status);

        // Balance should release pending days
        $balance = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $this->assertEquals(0, $balance->pendingDays);
        $this->assertEquals(22.0, $balance->availableDays);
    }

    /**
     * Test: Cannot cancel after HR approval
     */
    #[Group('integration')]
    public function testCannotCancelAfterHRApproval(): void
    {
        $this->markTestSkipped('Requires full database integration for end-to-end testing');

        // Create and approve request completely
        $this->vacationService->initializeBalance($this->testEmployeeId, 2026, 22.0);
        $request = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-09-01',
            '2026-09-05',
            'September vacation'
        );

        $this->vacationService->approveByManager($request->id, $this->testManagerId, 'OK');
        $this->vacationService->approveByHR($request->id, $this->testHrId, 'OK');

        // Try to cancel - should fail
        $this->vacationService->cancel($request->id, $this->testEmployeeId);
    }

    /**
     * Test: Multiple requests in same year
     */
    #[Group('integration')]
    public function testMultipleRequestsSameYear(): void
    {
        $this->markTestSkipped('Requires full database integration for end-to-end testing');
        $this->vacationService->initializeBalance($this->testEmployeeId, 2026, 22.0);

        // First request: 5 days
        $request1 = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-07-01',
            '2026-07-05',
            'First vacation'
        );
        $this->vacationService->approveByManager($request1->id, $this->testManagerId, 'OK');
        $this->vacationService->approveByHR($request1->id, $this->testHrId, 'OK');

        // Second request: 5 days
        $request2 = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-08-01',
            '2026-08-05',
            'Second vacation'
        );
        $this->vacationService->approveByManager($request2->id, $this->testManagerId, 'OK');
        $this->vacationService->approveByHR($request2->id, $this->testHrId, 'OK');

        // Check final balance
        $balance = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $totalUsed = $request1->totalDays + $request2->totalDays;

        $this->assertEquals($totalUsed, $balance->usedDays);
        $this->assertEquals(0, $balance->pendingDays);
        $this->assertEquals(22.0 - $totalUsed, $balance->availableDays);
    }

    /**
     * Test: Cannot create request exceeding available balance
     */
    #[Group('integration')]
    public function testCannotExceedAvailableBalance(): void
    {
        $this->markTestSkipped('Requires full database integration for end-to-end testing');

        // Initialize with only 10 days
        $this->vacationService->initializeBalance($this->testEmployeeId, 2026, 10.0);

        // First request uses 8 days
        $request1 = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-07-01',
            '2026-07-10',
            'First vacation'
        );

        // Try to create another request for 5 days (should fail - only 2 left)
        $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-08-01',
            '2026-08-07',
            'Second vacation - should fail'
        );
    }

    /**
     * Test: HR can reject even after manager approval
     */
    #[Group('integration')]
    public function testHRCanRejectAfterManagerApproval(): void
    {
        $this->markTestSkipped('Requires full database integration for end-to-end testing');
        $this->vacationService->initializeBalance($this->testEmployeeId, 2026, 22.0);

        $request = $this->vacationService->createRequest(
            $this->testEmployeeId,
            '2026-06-01',
            '2026-06-05',
            'June vacation'
        );

        // Manager approves
        $this->vacationService->approveByManager($request->id, $this->testManagerId, 'OK');

        // HR rejects (final decision)
        $rejected = $this->vacationService->reject(
            $request->id,
            $this->testHrId,
            'Company-wide freeze on vacations in June'
        );

        $this->assertEquals('REJECTED', $rejected->status);

        // Balance should release pending days
        $balance = $this->vacationService->getBalance($this->testEmployeeId, 2026);
        $this->assertEquals(0, $balance->pendingDays);
        $this->assertEquals(0, $balance->usedDays);
    }
}
