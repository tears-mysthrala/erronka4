<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Models;

/**
 * VacationRequest Model
 *
 * Represents a vacation request from an employee
 */
class VacationRequest
{
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_MANAGER_APPROVED = 'MANAGER_APPROVED';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_CANCELLED = 'CANCELLED';

    public function __construct(
        public string|int|null $id = null,
        public string|int|null $employeeId = null,
        public ?string $employeeFirstName = null,
        public ?string $employeeLastName = null,
        public ?string $employeeEmail = null,
        public ?string $employeeDepartment = null,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?float $totalDays = null,
        public ?string $requestDate = null,
        public string $status = self::STATUS_PENDING,
        public ?string $notes = null,
        public ?string $managerApprovalDate = null,
        public string|int|null $managerApprovalBy = null,
        public ?string $managerApprovalNotes = null,
        public ?string $hrApprovalDate = null,
        public string|int|null $hrApprovalBy = null,
        public ?string $hrApprovalNotes = null,
        public ?string $rejectionReason = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {
    }

    /**
     * Create from database row
     */
    public static function fromArray(array $data): self
    {
        $request = new self(
            id: $data['id'] ?? null,
            employeeId: $data['employee_id'] ?? null,
            employeeFirstName: $data['employee_first_name'] ?? null,
            employeeLastName: $data['employee_last_name'] ?? null,
            employeeEmail: $data['employee_email'] ?? null,
            employeeDepartment: $data['employee_department'] ?? null,
            startDate: $data['start_date'] ?? null,
            endDate: $data['end_date'] ?? null,
            totalDays: isset($data['total_days']) ? (float)$data['total_days'] : null,
            requestDate: $data['request_date'] ?? null,
            status: $data['status'] ?? self::STATUS_PENDING,
            notes: $data['notes'] ?? null,
            managerApprovalDate: $data['manager_approval_date'] ?? null,
            managerApprovalBy: $data['manager_approval_by'] ?? null,
            managerApprovalNotes: $data['manager_approval_notes'] ?? null,
            hrApprovalDate: $data['hr_approval_date'] ?? null,
            hrApprovalBy: $data['hr_approval_by'] ?? null,
            hrApprovalNotes: $data['hr_approval_notes'] ?? null,
            rejectionReason: $data['rejection_reason'] ?? null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null
        );

        return $request;
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'employee_first_name' => $this->employeeFirstName,
            'employee_last_name' => $this->employeeLastName,
            'employee_email' => $this->employeeEmail,
            'employee_department' => $this->employeeDepartment,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'total_days' => $this->totalDays,
            'request_date' => $this->requestDate,
            'status' => $this->status,
            'notes' => $this->notes,
            'manager_approval_date' => $this->managerApprovalDate,
            'manager_approval_by' => $this->managerApprovalBy,
            'manager_approval_notes' => $this->managerApprovalNotes,
            'hr_approval_date' => $this->hrApprovalDate,
            'hr_approval_by' => $this->hrApprovalBy,
            'hr_approval_notes' => $this->hrApprovalNotes,
            'rejection_reason' => $this->rejectionReason,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];

        return $result;
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if request can be approved by manager
     */
    public function canBeApprovedByManager(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request can be approved by HR
     */
    public function canBeApprovedByHR(): bool
    {
        return $this->status === self::STATUS_MANAGER_APPROVED;
    }
}
