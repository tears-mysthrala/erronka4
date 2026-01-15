<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Services\VacationService;
use ZabalaGailetak\HrPortal\Services\AuditLogger;

/**
 * VacationController
 * 
 * Handles vacation and leave requests
 */
class VacationController
{
    private VacationService $vacationService;
    private AuditLogger $auditLogger;

    public function __construct(VacationService $vacationService, AuditLogger $auditLogger)
    {
        $this->vacationService = $vacationService;
        $this->auditLogger = $auditLogger;
    }

    /**
     * GET /api/vacations/balance - Get vacation balance for current user
     */
    public function getBalance(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $year = $request->getQuery('year') ?? (int)date('Y');

        $balance = $this->vacationService->getBalance($user['employee_id'], (int)$year);
        
        if (!$balance) {
            $balance = $this->vacationService->initializeBalance($user['employee_id'], (int)$year);
        }

        return Response::json($balance->toArray());
    }

    /**
     * GET /api/vacations/balance/:employeeId - Get balance for specific employee (RRHH/Admin only)
     */
    public function getBalanceByEmployee(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $employeeId = (int)$request->getAttribute('employeeId');
        $year = $request->getQuery('year') ?? (int)date('Y');

        // Check permissions
        if (!in_array($user['role'], ['ADMIN', 'RRHH_MGR']) && $user['employee_id'] !== $employeeId) {
            return Response::json(['error' => 'Unauthorized'], 403);
        }

        $balance = $this->vacationService->getBalance($employeeId, (int)$year);
        
        if (!$balance) {
            $balance = $this->vacationService->initializeBalance($employeeId, (int)$year);
        }

        return Response::json($balance->toArray());
    }

    /**
     * POST /api/vacations/requests - Create vacation request
     */
    public function createRequest(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $data = $request->getParsedBody();

        // Validate required fields
        if (empty($data['start_date']) || empty($data['end_date'])) {
            return Response::json([
                'error' => 'Hasiera eta amaiera datak beharrezkoak dira / Fechas inicio y fin requeridas'
            ], 400);
        }

        // Validate dates
        if (strtotime($data['start_date']) < time()) {
            return Response::json([
                'error' => 'Data ezin da iraganekoa izan / La fecha no puede ser del pasado'
            ], 400);
        }

        if (strtotime($data['end_date']) < strtotime($data['start_date'])) {
            return Response::json([
                'error' => 'Amaiera data hasiera baino beranduagoa izan behar da / Fecha fin debe ser posterior'
            ], 400);
        }

        try {
            $vacationRequest = $this->vacationService->createRequest(
                $user['employee_id'],
                $data['start_date'],
                $data['end_date'],
                $data['notes'] ?? null
            );

            $this->auditLogger->logCreate(
                entityType: 'vacation_request',
                entityId: (string)$vacationRequest->id,
                newValues: [
                    'employee_id' => $user['employee_id'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'notes' => $data['notes'] ?? null
                ],
                userId: (string)$user['id'],
                userEmail: $user['email'] ?? '',
                userRole: $user['role'] ?? 'EMPLEADO',
                ipAddress: $_SERVER['REMOTE_ADDR'] ?? null,
                userAgent: $_SERVER['HTTP_USER_AGENT'] ?? null
            );

            return Response::json($vacationRequest->toArray(), 201);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * GET /api/vacations/requests - Get all requests for current user
     */
    public function getMyRequests(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $year = $request->getQuery('year') ? (int)$request->getQuery('year') : null;

        $requests = $this->vacationService->getEmployeeRequests($user['employee_id'], $year);

        return Response::json([
            'requests' => array_map(fn($r) => $r->toArray(), $requests)
        ]);
    }

    /**
     * GET /api/vacations/requests/:id - Get single request details
     */
    public function getRequest(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $requestId = (int)$request->getAttribute('requestId');

        $vacationRequest = $this->vacationService->getRequest($requestId);

        if (!$vacationRequest) {
            return Response::json(['error' => 'Request not found'], 404);
        }

        // Check permissions
        $canView = $user['employee_id'] === $vacationRequest->employeeId
            || in_array($user['role'], ['ADMIN', 'RRHH_MGR']);

        if (!$canView) {
            return Response::json(['error' => 'Unauthorized'], 403);
        }

        return Response::json($vacationRequest->toArray());
    }

    /**
     * GET /api/vacations/pending/manager - Get pending requests for manager approval
     */
    public function getPendingManagerRequests(Request $request): Response
    {
        $user = $request->getAttribute('user');

        // Only managers and RRHH can see this
        if (!in_array($user['role'], ['JEFE_SECCION', 'RRHH_MGR', 'ADMIN'])) {
            return Response::json(['error' => 'Unauthorized'], 403);
        }

        $departmentId = null;
        // If manager, only show their department
        if ($user['role'] === 'JEFE_SECCION' && isset($user['department'])) {
            $departmentId = $user['department'];
        }

        $requests = $this->vacationService->getPendingManagerRequests($departmentId);

        return Response::json([
            'requests' => array_map(fn($r) => $r->toArray(), $requests)
        ]);
    }

    /**
     * GET /api/vacations/pending/hr - Get pending requests for HR approval
     */
    public function getPendingHRRequests(Request $request): Response
    {
        $user = $request->getAttribute('user');

        // Only RRHH and Admin can see this
        if (!in_array($user['role'], ['RRHH_MGR', 'ADMIN'])) {
            return Response::json(['error' => 'Unauthorized'], 403);
        }

        $requests = $this->vacationService->getPendingHRRequests();

        return Response::json([
            'requests' => array_map(fn($r) => $r->toArray(), $requests)
        ]);
    }

    /**
     * POST /api/vacations/requests/:id/approve-manager - Approve by manager
     */
    public function approveByManager(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $requestId = (int)$request->getAttribute('requestId');
        $data = $request->getParsedBody();

        // Only managers and RRHH can approve
        if (!in_array($user['role'], ['JEFE_SECCION', 'RRHH_MGR', 'ADMIN'])) {
            return Response::json(['error' => 'Unauthorized'], 403);
        }

        try {
            $vacationRequest = $this->vacationService->approveByManager(
                $requestId,
                $user['id'],
                $data['notes'] ?? null
            );

            $this->auditLogger->logUpdate(
                entityType: 'vacation_request',
                entityId: (string)$requestId,
                oldValues: ['status' => 'PENDING'],
                newValues: ['status' => 'MANAGER_APPROVED'],
                userId: (string)$user['id'],
                userEmail: $user['email'] ?? '',
                userRole: $user['role'] ?? 'MANAGER',
                ipAddress: $_SERVER['REMOTE_ADDR'] ?? null,
                userAgent: $_SERVER['HTTP_USER_AGENT'] ?? null
            );

            return Response::json($vacationRequest->toArray());
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/vacations/requests/:id/approve-hr - Final approval by HR
     */
    public function approveByHR(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $requestId = (int)$request->getAttribute('requestId');
        $data = $request->getParsedBody();

        // Only RRHH and Admin can give final approval
        if (!in_array($user['role'], ['RRHH_MGR', 'ADMIN'])) {
            return Response::json(['error' => 'Unauthorized'], 403);
        }

        try {
            $vacationRequest = $this->vacationService->approveByHR(
                $requestId,
                $user['id'],
                $data['notes'] ?? null
            );

            $this->auditLogger->logUpdate(
                entityType: 'vacation_request',
                entityId: (string)$requestId,
                oldValues: ['status' => 'MANAGER_APPROVED'],
                newValues: ['status' => 'APPROVED'],
                userId: (string)$user['id'],
                userEmail: $user['email'] ?? '',
                userRole: $user['role'] ?? 'RRHH',
                ipAddress: $_SERVER['REMOTE_ADDR'] ?? null,
                userAgent: $_SERVER['HTTP_USER_AGENT'] ?? null
            );

            return Response::json($vacationRequest->toArray());
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/vacations/requests/:id/reject - Reject request
     */
    public function reject(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $requestId = (int)$request->getAttribute('requestId');
        $data = $request->getParsedBody();

        // Only managers and RRHH can reject
        if (!in_array($user['role'], ['JEFE_SECCION', 'RRHH_MGR', 'ADMIN'])) {
            return Response::json(['error' => 'Unauthorized'], 403);
        }

        if (empty($data['reason'])) {
            return Response::json([
                'error' => 'Arrazoia beharrezkoa da / Motivo requerido'
            ], 400);
        }

        try {
            $vacationRequest = $this->vacationService->reject(
                $requestId,
                $user['id'],
                $data['reason']
            );

            $this->auditLogger->logUpdate(
                entityType: 'vacation_request',
                entityId: (string)$requestId,
                oldValues: ['status' => 'PENDING'],
                newValues: ['status' => 'REJECTED', 'rejection_reason' => $data['reason']],
                userId: (string)$user['id'],
                userEmail: $user['email'] ?? '',
                userRole: $user['role'] ?? '',
                ipAddress: $_SERVER['REMOTE_ADDR'] ?? null,
                userAgent: $_SERVER['HTTP_USER_AGENT'] ?? null
            );

            return Response::json($vacationRequest->toArray());
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/vacations/requests/:id/cancel - Cancel request (by employee)
     */
    public function cancel(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $requestId = (int)$request->getAttribute('requestId');

        try {
            $vacationRequest = $this->vacationService->cancel($requestId, $user['employee_id']);

            $this->auditLogger->logUpdate(
                entityType: 'vacation_request',
                entityId: (string)$requestId,
                oldValues: ['status' => 'PENDING'],
                newValues: ['status' => 'CANCELLED'],
                userId: (string)$user['id'],
                userEmail: $user['email'] ?? '',
                userRole: $user['role'] ?? 'EMPLEADO',
                ipAddress: $_SERVER['REMOTE_ADDR'] ?? null,
                userAgent: $_SERVER['HTTP_USER_AGENT'] ?? null
            );

            return Response::json($vacationRequest->toArray());
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * GET /api/vacations/calendar - Get vacation calendar
     */
    public function getCalendar(Request $request): Response
    {
        $user = $request->getAttribute('user');
        $year = $request->getQuery('year') ? (int)$request->getQuery('year') : null;
        $month = $request->getQuery('month') ? (int)$request->getQuery('month') : null;
        
        $departmentId = null;
        // If manager, only show their department
        if ($user['role'] === 'JEFE_SECCION' && isset($user['department'])) {
            $departmentId = $user['department'];
        }

        $calendar = $this->vacationService->getCalendar($departmentId, $year, $month);

        return Response::json(['calendar' => $calendar]);
    }
}
