<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Models;

/**
 * VacationBalance Model
 * 
 * Represents an employee's vacation days balance for a specific year
 */
class VacationBalance
{
    public function __construct(
        public ?int $id = null,
        public ?int $employeeId = null,
        public ?int $year = null,
        public float $totalDays = 22.0,
        public float $usedDays = 0.0,
        public float $pendingDays = 0.0,
        public ?float $availableDays = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {
        // Calculate available days if not provided
        if ($this->availableDays === null) {
            $this->availableDays = $this->totalDays - $this->usedDays - $this->pendingDays;
        }
    }

    /**
     * Create from database row
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            employeeId: isset($data['employee_id']) ? (int)$data['employee_id'] : null,
            year: isset($data['year']) ? (int)$data['year'] : null,
            totalDays: isset($data['total_days']) ? (float)$data['total_days'] : 22.0,
            usedDays: isset($data['used_days']) ? (float)$data['used_days'] : 0.0,
            pendingDays: isset($data['pending_days']) ? (float)$data['pending_days'] : 0.0,
            availableDays: isset($data['available_days']) ? (float)$data['available_days'] : null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null
        );
    }

    /**
     * Convert to array for JSON serialization
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'year' => $this->year,
            'total_days' => $this->totalDays,
            'used_days' => $this->usedDays,
            'pending_days' => $this->pendingDays,
            'available_days' => $this->availableDays,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}
