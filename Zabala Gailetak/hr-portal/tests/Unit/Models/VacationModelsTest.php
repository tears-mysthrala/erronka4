<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use ZabalaGailetak\HrPortal\Models\VacationRequest;
use ZabalaGailetak\HrPortal\Models\VacationBalance;

/**
 * Tests for Vacation Models
 */
class VacationModelsTest extends TestCase
{
    /**
     * Test: VacationRequest model creation
     */
    public function testVacationRequestCreation(): void
    {
        $data = [
            'id' => 1,
            'employee_id' => 5,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
            'total_days' => 4.0,
            'status' => 'PENDING',
            'notes' => 'Summer vacation',
            'created_at' => '2026-06-01 10:00:00'
        ];

        $request = VacationRequest::fromArray($data);

        $this->assertEquals(1, $request->id);
        $this->assertEquals(5, $request->employeeId);
        $this->assertEquals('2026-07-01', $request->startDate);
        $this->assertEquals('2026-07-05', $request->endDate);
        $this->assertEquals(4.0, $request->totalDays);
        $this->assertEquals('PENDING', $request->status);
        $this->assertEquals('Summer vacation', $request->notes);
    }

    /**
     * Test: VacationRequest helper methods
     */
    public function testVacationRequestHelpers(): void
    {
        // Pending request
        $pending = VacationRequest::fromArray([
            'id' => 1,
            'employee_id' => 5,
            'status' => 'PENDING'
        ]);

        $this->assertTrue($pending->isPending());
        $this->assertFalse($pending->isApproved());
        $this->assertTrue($pending->canBeApprovedByManager());
        $this->assertFalse($pending->canBeApprovedByHR());

        // Manager approved request
        $managerApproved = VacationRequest::fromArray([
            'id' => 2,
            'employee_id' => 5,
            'status' => 'MANAGER_APPROVED',
            'manager_approved_by' => 3,
            'manager_approved_at' => '2026-06-02 10:00:00'
        ]);

        $this->assertFalse($managerApproved->isPending());
        $this->assertFalse($managerApproved->isApproved());
        $this->assertFalse($managerApproved->canBeApprovedByManager());
        $this->assertTrue($managerApproved->canBeApprovedByHR());

        // Fully approved request
        $approved = VacationRequest::fromArray([
            'id' => 3,
            'employee_id' => 5,
            'status' => 'APPROVED',
            'manager_approved_by' => 3,
            'hr_approved_by' => 4
        ]);

        $this->assertFalse($approved->isPending());
        $this->assertTrue($approved->isApproved());
        $this->assertFalse($approved->canBeApprovedByManager());
        $this->assertFalse($approved->canBeApprovedByHR());
    }

    /**
     * Test: VacationRequest toArray method
     */
    public function testVacationRequestToArray(): void
    {
        $data = [
            'id' => 1,
            'employee_id' => 5,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
            'total_days' => 4.0,
            'status' => 'PENDING'
        ];

        $request = VacationRequest::fromArray($data);
        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(1, $array['id']);
        $this->assertEquals(5, $array['employee_id']);
        $this->assertEquals('2026-07-01', $array['start_date']);
        $this->assertEquals('PENDING', $array['status']);
    }

    /**
     * Test: VacationBalance model creation
     */
    public function testVacationBalanceCreation(): void
    {
        $data = [
            'id' => 1,
            'employee_id' => 5,
            'year' => 2026,
            'total_days' => 22.0,
            'used_days' => 5.0,
            'pending_days' => 3.0,
            'available_days' => 14.0
        ];

        $balance = VacationBalance::fromArray($data);

        $this->assertEquals(1, $balance->id);
        $this->assertEquals(5, $balance->employeeId);
        $this->assertEquals(2026, $balance->year);
        $this->assertEquals(22.0, $balance->totalDays);
        $this->assertEquals(5.0, $balance->usedDays);
        $this->assertEquals(3.0, $balance->pendingDays);
        $this->assertEquals(14.0, $balance->availableDays);
    }

    /**
     * Test: VacationBalance toArray method
     */
    public function testVacationBalanceToArray(): void
    {
        $data = [
            'employee_id' => 5,
            'year' => 2026,
            'total_days' => 22.0,
            'used_days' => 5.0,
            'pending_days' => 3.0,
            'available_days' => 14.0
        ];

        $balance = VacationBalance::fromArray($data);
        $array = $balance->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(5, $array['employee_id']);
        $this->assertEquals(2026, $array['year']);
        $this->assertEquals(22.0, $array['total_days']);
        $this->assertEquals(14.0, $array['available_days']);
    }

    /**
     * Test: VacationBalance with null values
     */
    public function testVacationBalanceWithDefaults(): void
    {
        $data = [
            'employee_id' => 5,
            'year' => 2026,
            'total_days' => 22.0
        ];

        $balance = VacationBalance::fromArray($data);

        // Should have default values for optional fields
        $this->assertEquals(5, $balance->employeeId);
        $this->assertEquals(2026, $balance->year);
        $this->assertEquals(22.0, $balance->totalDays);
    }

    /**
     * Test: VacationRequest status constants
     */
    public function testVacationRequestStatusConstants(): void
    {
        $this->assertEquals('PENDING', VacationRequest::STATUS_PENDING);
        $this->assertEquals('MANAGER_APPROVED', VacationRequest::STATUS_MANAGER_APPROVED);
        $this->assertEquals('APPROVED', VacationRequest::STATUS_APPROVED);
        $this->assertEquals('REJECTED', VacationRequest::STATUS_REJECTED);
        $this->assertEquals('CANCELLED', VacationRequest::STATUS_CANCELLED);
    }
}
