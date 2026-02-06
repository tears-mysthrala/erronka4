<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Models;

/**
 * Payslip Model
 * Represents employee payroll information
 */
class Payslip
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $employeeId,
        public readonly string $periodStart,
        public readonly string $periodEnd,
        public readonly float $baseSalary,
        public readonly float $extraHours,
        public readonly float $bonuses,
        public readonly float $commissions,
        public readonly float $deductions,
        public readonly float $taxes,
        public readonly float $socialSecurity,
        public readonly float $otherDeductions,
        public readonly float $grossSalary,
        public readonly float $netSalary,
        public readonly ?string $pdfPath,
        public readonly ?string $pdfFilename,
        public readonly ?string $notes,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt
    ) {
    }

    /**
     * Get month and year from period
     */
    public function getMonth(): int
    {
        return (int) date('n', strtotime($this->periodStart));
    }

    public function getYear(): int
    {
        return (int) date('Y', strtotime($this->periodStart));
    }

    /**
     * Get month name in Basque
     */
    public function getMonthNameBasque(): string
    {
        $months = [
            1 => 'Urtarrila', 2 => 'Otsaila', 3 => 'Martxoa',
            4 => 'Apirila', 5 => 'Maiatza', 6 => 'Ekaina',
            7 => 'Uztaila', 8 => 'Abuztua', 9 => 'Iraila',
            10 => 'Urria', 11 => 'Azaroa', 12 => 'Abendua'
        ];
        return $months[$this->getMonth()] ?? '';
    }

    /**
     * Get month name in Spanish
     */
    public function getMonthNameSpanish(): string
    {
        $months = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $months[$this->getMonth()] ?? '';
    }

    /**
     * Format currency value
     */
    public static function formatCurrency(float $amount): string
    {
        return number_format($amount, 2, ',', '.') . ' €';
    }

    /**
     * Convert to array for API responses
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'period_start' => $this->periodStart,
            'period_end' => $this->periodEnd,
            'month' => $this->getMonth(),
            'year' => $this->getYear(),
            'month_name_eu' => $this->getMonthNameBasque(),
            'month_name_es' => $this->getMonthNameSpanish(),
            'base_salary' => $this->baseSalary,
            'extra_hours' => $this->extraHours,
            'bonuses' => $this->bonuses,
            'commissions' => $this->commissions,
            'deductions' => $this->deductions,
            'taxes' => $this->taxes,
            'social_security' => $this->socialSecurity,
            'other_deductions' => $this->otherDeductions,
            'gross_salary' => $this->grossSalary,
            'net_salary' => $this->netSalary,
            'pdf_path' => $this->pdfPath,
            'pdf_filename' => $this->pdfFilename,
            'notes' => $this->notes,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }

    /**
     * Create from database row
     */
    public static function fromDatabase(array $row): self
    {
        return new self(
            id: $row['id'] ?? null,
            employeeId: $row['employee_id'],
            periodStart: $row['period_start'],
            periodEnd: $row['period_end'],
            baseSalary: (float) $row['base_salary'],
            extraHours: (float) ($row['extra_hours'] ?? 0),
            bonuses: (float) ($row['bonuses'] ?? 0),
            commissions: (float) ($row['commissions'] ?? 0),
            deductions: (float) ($row['deductions'] ?? 0),
            taxes: (float) ($row['taxes'] ?? 0),
            socialSecurity: (float) ($row['social_security'] ?? 0),
            otherDeductions: (float) ($row['other_deductions'] ?? 0),
            grossSalary: (float) $row['gross_salary'],
            netSalary: (float) $row['net_salary'],
            pdfPath: $row['pdf_path'] ?? null,
            pdfFilename: $row['pdf_filename'] ?? null,
            notes: $row['notes'] ?? null,
            createdAt: $row['created_at'] ?? null,
            updatedAt: $row['updated_at'] ?? null
        );
    }
}
