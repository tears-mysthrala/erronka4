<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Services;

use PDO;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Models\VacationBalance;
use ZabalaGailetak\HrPortal\Models\VacationRequest;
use DateTime;
use DateInterval;
use DatePeriod;

/**
 * VacationService
 * 
 * Business logic for vacation management
 */
class VacationService
{
    private PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    /**
     * Get vacation balance for employee and year
     */
    public function getBalance(int $employeeId, int $year): ?VacationBalance
    {
        $stmt = $this->db->prepare('
            SELECT * FROM vacation_balances
            WHERE employee_id = :employee_id AND year = :year
        ');
        $stmt->execute([
            'employee_id' => $employeeId,
            'year' => $year
        ]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? VacationBalance::fromArray($data) : null;
    }

    /**
     * Initialize balance for employee if not exists
     */
    public function initializeBalance(int $employeeId, int $year, float $totalDays = 22.0): VacationBalance
    {
        $existing = $this->getBalance($employeeId, $year);
        if ($existing) {
            return $existing;
        }

        $stmt = $this->db->prepare('
            INSERT INTO vacation_balances (employee_id, year, total_days)
            VALUES (:employee_id, :year, :total_days)
            RETURNING *
        ');
        $stmt->execute([
            'employee_id' => $employeeId,
            'year' => $year,
            'total_days' => $totalDays
        ]);

        return VacationBalance::fromArray($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Create vacation request
     */
    public function createRequest(
        int $employeeId,
        string $startDate,
        string $endDate,
        ?string $notes = null
    ): VacationRequest {
        // Calculate business days (excluding weekends and public holidays)
        $totalDays = $this->calculateBusinessDays($startDate, $endDate);

        // Check if employee has enough available days
        $year = (int)date('Y', strtotime($startDate));
        $balance = $this->getBalance($employeeId, $year);
        
        if (!$balance) {
            $balance = $this->initializeBalance($employeeId, $year);
        }

        if ($balance->availableDays < $totalDays) {
            throw new \Exception('Opor egun nahikorik ez dago / No hay suficientes días disponibles');
        }

        // Create request
        $stmt = $this->db->prepare('
            INSERT INTO vacation_requests 
            (employee_id, start_date, end_date, total_days, notes, status)
            VALUES (:employee_id, :start_date, :end_date, :total_days, :notes, :status)
            RETURNING *
        ');
        
        $stmt->execute([
            'employee_id' => $employeeId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'notes' => $notes,
            'status' => VacationRequest::STATUS_PENDING
        ]);

        return VacationRequest::fromArray($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Get all requests for employee
     */
    public function getEmployeeRequests(int $employeeId, ?int $year = null): array
    {
        $sql = '
            SELECT vr.*, 
                   e.first_name as employee_first_name,
                   e.last_name as employee_last_name,
                   e.email as employee_email,
                   e.department as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.employee_id = :employee_id
        ';

        if ($year) {
            $sql .= ' AND EXTRACT(YEAR FROM vr.start_date) = :year';
        }

        $sql .= ' ORDER BY vr.start_date DESC';

        $stmt = $this->db->prepare($sql);
        $params = ['employee_id' => $employeeId];
        if ($year) {
            $params['year'] = $year;
        }
        $stmt->execute($params);

        $requests = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $requests[] = VacationRequest::fromArray($row);
        }

        return $requests;
    }

    /**
     * Get pending requests for manager approval
     */
    public function getPendingManagerRequests(?int $departmentId = null): array
    {
        $sql = '
            SELECT vr.*, 
                   e.first_name as employee_first_name,
                   e.last_name as employee_last_name,
                   e.email as employee_email,
                   e.department as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.status = :status
        ';

        if ($departmentId) {
            $sql .= ' AND e.department = :department';
        }

        $sql .= ' ORDER BY vr.request_date ASC';

        $stmt = $this->db->prepare($sql);
        $params = ['status' => VacationRequest::STATUS_PENDING];
        if ($departmentId) {
            $params['department'] = $departmentId;
        }
        $stmt->execute($params);

        $requests = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $requests[] = VacationRequest::fromArray($row);
        }

        return $requests;
    }

    /**
     * Get pending requests for HR approval
     */
    public function getPendingHRRequests(): array
    {
        $stmt = $this->db->prepare('
            SELECT vr.*, 
                   e.first_name as employee_first_name,
                   e.last_name as employee_last_name,
                   e.email as employee_email,
                   e.department as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.status = :status
            ORDER BY vr.request_date ASC
        ');
        $stmt->execute(['status' => VacationRequest::STATUS_MANAGER_APPROVED]);

        $requests = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $requests[] = VacationRequest::fromArray($row);
        }

        return $requests;
    }

    /**
     * Approve request by manager
     */
    public function approveByManager(int $requestId, int $managerId, ?string $notes = null): VacationRequest
    {
        $request = $this->getRequest($requestId);
        if (!$request) {
            throw new \Exception('Eskaera ez da aurkitu / Solicitud no encontrada');
        }

        if (!$request->canBeApprovedByManager()) {
            throw new \Exception('Eskaera ezin da onartu / La solicitud no puede ser aprobada');
        }

        $stmt = $this->db->prepare('
            UPDATE vacation_requests
            SET status = :status,
                manager_approval_date = CURRENT_TIMESTAMP,
                manager_approval_by = :manager_id,
                manager_approval_notes = :notes,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
            RETURNING *
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_MANAGER_APPROVED,
            'manager_id' => $managerId,
            'notes' => $notes,
            'id' => $requestId
        ]);

        return VacationRequest::fromArray($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Approve request by HR (final approval)
     */
    public function approveByHR(int $requestId, int $hrId, ?string $notes = null): VacationRequest
    {
        $request = $this->getRequest($requestId);
        if (!$request) {
            throw new \Exception('Eskaera ez da aurkitu / Solicitud no encontrada');
        }

        if (!$request->canBeApprovedByHR()) {
            throw new \Exception('Eskaera ezin da onartu / La solicitud no puede ser aprobada');
        }

        $stmt = $this->db->prepare('
            UPDATE vacation_requests
            SET status = :status,
                hr_approval_date = CURRENT_TIMESTAMP,
                hr_approval_by = :hr_id,
                hr_approval_notes = :notes,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
            RETURNING *
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_APPROVED,
            'hr_id' => $hrId,
            'notes' => $notes,
            'id' => $requestId
        ]);

        return VacationRequest::fromArray($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Reject request
     */
    public function reject(int $requestId, int $approverId, string $reason): VacationRequest
    {
        $stmt = $this->db->prepare('
            UPDATE vacation_requests
            SET status = :status,
                rejection_reason = :reason,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
            RETURNING *
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_REJECTED,
            'reason' => $reason,
            'id' => $requestId
        ]);

        return VacationRequest::fromArray($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Cancel request (by employee)
     */
    public function cancel(int $requestId, int $employeeId): VacationRequest
    {
        $request = $this->getRequest($requestId);
        if (!$request || $request->employeeId !== $employeeId) {
            throw new \Exception('Eskaera ez da aurkitu / Solicitud no encontrada');
        }

        if ($request->isApproved() && strtotime($request->startDate) <= time()) {
            throw new \Exception('Ezin da ezeztatu hasitako oporrak / No se pueden cancelar vacaciones iniciadas');
        }

        $stmt = $this->db->prepare('
            UPDATE vacation_requests
            SET status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
            RETURNING *
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_CANCELLED,
            'id' => $requestId
        ]);

        return VacationRequest::fromArray($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Get single request by ID
     */
    public function getRequest(int $id): ?VacationRequest
    {
        $stmt = $this->db->prepare('
            SELECT vr.*, 
                   e.first_name as employee_first_name,
                   e.last_name as employee_last_name,
                   e.email as employee_email,
                   e.department as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.id = :id
        ');
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? VacationRequest::fromArray($data) : null;
    }

    /**
     * Calculate business days between two dates (excluding weekends and public holidays)
     */
    private function calculateBusinessDays(string $startDate, string $endDate): float
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day'); // Include end date

        // Get public holidays for the year
        $holidays = $this->getPublicHolidays((int)$start->format('Y'));

        $days = 0.0;
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        foreach ($period as $date) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($date->format('N') >= 6) {
                continue;
            }

            // Skip public holidays
            $dateStr = $date->format('Y-m-d');
            if (in_array($dateStr, $holidays)) {
                continue;
            }

            $days += 1.0;
        }

        return $days;
    }

    /**
     * Get public holidays for a specific year
     */
    private function getPublicHolidays(int $year): array
    {
        $stmt = $this->db->prepare('
            SELECT holiday_date FROM public_holidays
            WHERE year = :year
        ');
        $stmt->execute(['year' => $year]);

        $holidays = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $holidays[] = $row['holiday_date'];
        }

        return $holidays;
    }

    /**
     * Get calendar view of vacations for a department or company
     */
    public function getCalendar(?int $departmentId = null, ?int $year = null, ?int $month = null): array
    {
        $year = $year ?? (int)date('Y');
        
        $sql = '
            SELECT vr.*, 
                   e.first_name, e.last_name, e.department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.status IN (:status1, :status2)
              AND EXTRACT(YEAR FROM vr.start_date) = :year
        ';

        $params = [
            'status1' => VacationRequest::STATUS_APPROVED,
            'status2' => VacationRequest::STATUS_MANAGER_APPROVED,
            'year' => $year
        ];

        if ($departmentId) {
            $sql .= ' AND e.department = :department';
            $params['department'] = $departmentId;
        }

        if ($month) {
            $sql .= ' AND (
                EXTRACT(MONTH FROM vr.start_date) = :month 
                OR EXTRACT(MONTH FROM vr.end_date) = :month
            )';
            $params['month'] = $month;
        }

        $sql .= ' ORDER BY vr.start_date ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $calendar = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $calendar[] = [
                'id' => $row['id'],
                'employee_name' => $row['first_name'] . ' ' . $row['last_name'],
                'department' => $row['department'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'total_days' => $row['total_days'],
                'status' => $row['status']
            ];
        }

        return $calendar;
    }
}
