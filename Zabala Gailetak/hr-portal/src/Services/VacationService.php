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
    public function getBalance(string|int $employeeId, int $year): ?VacationBalance
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
    public function initializeBalance(string|int $employeeId, int $year, float $totalDays = 22.0): VacationBalance
    {
        $existing = $this->getBalance($employeeId, $year);
        if ($existing) {
            return $existing;
        }

        $stmt = $this->db->prepare('
            INSERT INTO vacation_balances (employee_id, year, total_days)
            VALUES (:employee_id, :year, :total_days)
        ');
        $stmt->execute([
            'employee_id' => $employeeId,
            'year' => $year,
            'total_days' => $totalDays
        ]);

        return $this->getBalance($employeeId, $year);
    }

    /**
     * Create vacation request
     */
    public function createRequest(
        string|int $employeeId,
        string $startDate,
        string $endDate,
        ?string $notes = null
    ): VacationRequest {
        try {
            // Validate dates
            if (empty($startDate) || empty($endDate)) {
                throw new \Exception('Las fechas de inicio y fin son obligatorias');
            }

            $startTimestamp = strtotime($startDate);
            $endTimestamp = strtotime($endDate);
            $todayTimestamp = strtotime(date('Y-m-d'));

            if ($startTimestamp === false || $endTimestamp === false) {
                throw new \Exception('Las fechas proporcionadas no son válidas');
            }

            if ($startTimestamp > $endTimestamp) {
                throw new \Exception('La fecha de inicio no puede ser posterior a la fecha de fin');
            }

            if ($startTimestamp < $todayTimestamp) {
                throw new \Exception('No se pueden solicitar vacaciones para fechas pasadas');
            }

            // Calculate business days (excluding weekends and public holidays)
            $totalDays = $this->calculateBusinessDays($startDate, $endDate);

            if ($totalDays <= 0) {
                throw new \Exception('El período seleccionado no contiene días laborables válidos');
            }

            // Check if employee has enough available days
            $year = (int)date('Y', $startTimestamp);
            $balance = $this->getBalance($employeeId, $year);

            if (!$balance) {
                $balance = $this->initializeBalance($employeeId, $year);
            }

            if ($balance->availableDays < $totalDays) {
                throw new \Exception("No tienes suficientes días disponibles. Disponibles: {$balance->availableDays}, Solicitados: {$totalDays}");
            }

            // Check for overlapping requests
            $overlapCheck = $this->db->prepare('
                SELECT COUNT(*) FROM vacation_requests 
                WHERE employee_id = :employee_id 
                AND status IN (:pending_status, :manager_approved_status, :approved_status)
                AND (
                    (start_date <= :start_date AND end_date >= :start_date) OR
                    (start_date <= :end_date AND end_date >= :end_date) OR
                    (start_date >= :start_date AND end_date <= :end_date)
                )
            ');

            $overlapCheck->execute([
                'employee_id' => $employeeId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pending_status' => VacationRequest::STATUS_PENDING,
                'manager_approved_status' => VacationRequest::STATUS_MANAGER_APPROVED,
                'approved_status' => VacationRequest::STATUS_APPROVED
            ]);

            if ($overlapCheck->fetchColumn() > 0) {
                throw new \Exception('Ya tienes una solicitud de vacaciones para este período o que se solapa con estas fechas');
            }

            // Create request
            $stmt = $this->db->prepare('
                INSERT INTO vacation_requests 
                (employee_id, start_date, end_date, total_days, notes, status, request_date)
                VALUES (:employee_id, :start_date, :end_date, :total_days, :notes, :status, CURRENT_DATE)
                RETURNING id
            ');

            $result = $stmt->execute([
                'employee_id' => $employeeId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $totalDays,
                'notes' => $notes,
                'status' => VacationRequest::STATUS_PENDING
            ]);

            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                throw new \Exception('Error al crear la solicitud: ' . ($errorInfo[2] ?? 'Error desconocido'));
            }

            $id = $stmt->fetchColumn();

            if (!$id) {
                throw new \Exception('No se pudo obtener el ID de la solicitud creada');
            }

            return $this->getRequest($id);

        } catch (\Exception $e) {
            error_log("Error en createRequest: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all requests for employee
     */
    public function getEmployeeRequests(string|int $employeeId, ?int $year = null): array
    {
        $sql = '
            SELECT vr.*, 
                   e.first_name as employee_first_name,
                   e.last_name as employee_last_name,
                   u.email as employee_email,
                   d.name as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            JOIN departments d ON e.department_id = d.id
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
                   u.email as employee_email,
                   d.name as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            JOIN departments d ON e.department_id = d.id
            WHERE vr.status = :status
        ';

        if ($departmentId) {
            $sql .= ' AND e.department_id = :department';
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
                   u.email as employee_email,
                   d.name as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            JOIN departments d ON e.department_id = d.id
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
    public function approveByManager(
        string|int $requestId,
        string|int $managerId,
        ?string $notes = null
    ): VacationRequest {
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
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_MANAGER_APPROVED,
            'manager_id' => $managerId,
            'notes' => $notes,
            'id' => $requestId
        ]);

        return $this->getRequest($requestId);
    }

    /**
     * Approve request by HR (final approval)
     */
    public function approveByHR(string|int $requestId, string|int $hrId, ?string $notes = null): VacationRequest
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
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_APPROVED,
            'hr_id' => $hrId,
            'notes' => $notes,
            'id' => $requestId
        ]);

        return $this->getRequest($requestId);
    }

    /**
     * Reject request
     */
    public function reject(string|int $requestId, string|int $approverId, string $reason): VacationRequest
    {
        $stmt = $this->db->prepare('
            UPDATE vacation_requests
            SET status = :status,
                rejection_reason = :reason,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_REJECTED,
            'reason' => $reason,
            'id' => $requestId
        ]);

        return $this->getRequest($requestId);
    }

    /**
     * Cancel request (by employee)
     */
    public function cancel(string|int $requestId, string|int $employeeId): VacationRequest
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
        ');

        $stmt->execute([
            'status' => VacationRequest::STATUS_CANCELLED,
            'id' => $requestId
        ]);

        return $this->getRequest($requestId);
    }

    /**
     * Get single request by ID
     */
    public function getRequest(string|int $id): ?VacationRequest
    {
        $stmt = $this->db->prepare('
            SELECT vr.*, 
                   e.first_name as employee_first_name,
                   e.last_name as employee_last_name,
                   u.email as employee_email,
                   d.name as employee_department
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            JOIN departments d ON e.department_id = d.id
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
    public function getCalendar(string|int|null $departmentId = null, ?int $year = null, ?int $month = null): array
    {
        $year = $year ?? (int)date('Y');

        $sql = '
            SELECT vr.*, 
                   e.first_name, e.last_name, e.department_id
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
            $sql .= ' AND e.department_id = :department';
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
