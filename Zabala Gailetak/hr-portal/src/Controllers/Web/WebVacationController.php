<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers\Web;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Services\VacationService;
use ZabalaGailetak\HrPortal\Services\AuditLogger;
use PDO;
use Exception;

class WebVacationController
{
    private Database $db;
    private VacationService $vacationService;

    public function __construct(Database $db, VacationService $vacationService)
    {
        $this->db = $db;
        $this->vacationService = $vacationService;
    }

    public function index(Request $request): Response
    {
        $this->requireAuth();
        $employeeId = $this->getCurrentEmployeeId();

        if (!$employeeId) {
             return Response::view('vacations/index', ['error' => 'No tienes perfil de empleado asociado.']);
        }

        $year = (int)date('Y');
        $balance = $this->vacationService->getBalance($employeeId, $year)
                   ?? $this->vacationService->initializeBalance($employeeId, $year);

        $requests = $this->vacationService->getEmployeeRequests($employeeId, $year);

        $role = $_SESSION['user_role'] ?? 'employee';
        $isAdmin = $role === 'admin' || $role === 'hr_manager';
        $isDepartmentHead = $role === 'department_head';

        $pendingName = trim((string)$request->getQuery('pending_name', ''));
        $pendingEmail = trim((string)$request->getQuery('pending_email', ''));
        $historyName = trim((string)$request->getQuery('history_name', ''));
        $historyEmail = trim((string)$request->getQuery('history_email', ''));

        $pendingApprovals = [];
        $historyApprovals = [];

        if ($isDepartmentHead) {
            $departmentId = $this->getCurrentEmployeeDepartmentId();
            $pendingApprovals = $this->vacationService->getPendingManagerRequests(
                $departmentId,
                $pendingName,
                $pendingEmail
            );
            $historyApprovals = $this->vacationService->getRequestsHistory(
                $departmentId,
                $historyName,
                $historyEmail
            );
        } elseif ($isAdmin) {
            $pendingApprovals = $this->vacationService->getPendingHRRequests(
                $pendingName,
                $pendingEmail
            );
            $historyApprovals = $this->vacationService->getRequestsHistory(
                null,
                $historyName,
                $historyEmail
            );
        }

        $companySummary = null;
        if ($isAdmin) {
            // Calculate available_days dynamically for MySQL compatibility
            $stmt = $this->db->prepare('
                SELECT 
                    COUNT(DISTINCT employee_id) as employee_count,
                    COALESCE(SUM(total_days), 0) as total_days,
                    COALESCE(SUM(used_days), 0) as used_days,
                    COALESCE(SUM(pending_days), 0) as pending_days,
                    COALESCE(SUM(total_days - used_days - pending_days), 0) as available_days
                FROM vacation_balances
                WHERE year = :year
            ');
            $stmt->execute(['year' => $year]);
            $companySummary = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return Response::view('vacations/index', [
            'balance' => $balance,
            'requests' => $requests,
            'pendingApprovals' => $pendingApprovals,
            'historyApprovals' => $historyApprovals,
            'year' => $year,
            'isAdmin' => $isAdmin,
            'isDepartmentHead' => $isDepartmentHead,
            'pendingFilters' => [
                'name' => $pendingName,
                'email' => $pendingEmail
            ],
            'historyFilters' => [
                'name' => $historyName,
                'email' => $historyEmail
            ],
            'companySummary' => $companySummary
        ]);
    }

    public function requestForm(Request $request): Response
    {
        $this->requireAuth();
        return Response::view('vacations/create', ['errors' => []]);
    }

    public function create(Request $request): Response
    {
        $this->requireAuth();
        $data = $request->getParsedBody();
        $employeeId = $this->getCurrentEmployeeId();

        if (!$employeeId) {
            return Response::view('vacations/create', [
                'errors' => ['system' => 'No se pudo identificar al empleado actual']
            ]);
        }

        // Validate input data
        $errors = [];
        
        if (empty($data['start_date'])) {
            $errors['start_date'] = 'La fecha de inicio es obligatoria';
        }
        
        if (empty($data['end_date'])) {
            $errors['end_date'] = 'La fecha de fin es obligatoria';
        }
        
        if (!empty($errors)) {
            return Response::view('vacations/create', [
                'errors' => $errors,
                'old' => $data
            ]);
        }

        try {
            $vacationRequest = $this->vacationService->createRequest(
                $employeeId,
                $data['start_date'],
                $data['end_date'],
                $data['notes'] ?? null
            );
            
            // Add success message to session
            $_SESSION['success_message'] = 'Solicitud de vacaciones creada correctamente. Folio: ' . $vacationRequest->id;
            
            return Response::redirect('/vacations');
            
        } catch (Exception $e) {
            error_log("Vacation request creation error: " . $e->getMessage());
            
            return Response::view('vacations/create', [
                'errors' => ['system' => $e->getMessage()],
                'old' => $data
            ]);
        }
    }

    public function approve(Request $request, string $id): Response
    {
        $this->requireAuth();
        $role = $_SESSION['user_role'] ?? 'employee';
        $isAdmin = $role === 'admin' || $role === 'hr_manager';
        $isDepartmentHead = $role === 'department_head';

        if (!$isAdmin && !$isDepartmentHead) {
            return Response::redirect('/vacations');
        }

        try {
            $approverEmployeeId = $this->getCurrentEmployeeId();
            if (!$approverEmployeeId) {
                throw new Exception('No se pudo identificar al aprobador');
            }

            if ($isDepartmentHead) {
                $this->vacationService->approveByManager($id, $approverEmployeeId, 'Aprobado por jefe de departamento');
            } else {
                $this->vacationService->approveByHR($id, $approverEmployeeId, 'Aprobado desde panel web');
            }

            if ($request->isJson()) {
                return Response::json(['success' => true]);
            }

            return Response::redirect('/vacations');
        } catch (Exception $e) {
            if ($request->isJson()) {
                return Response::json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            return Response::redirect('/vacations');
        }
    }

    public function reject(Request $request, string $id): Response
    {
        $this->requireAuth();
        $role = $_SESSION['user_role'] ?? 'employee';
        $isAdmin = $role === 'admin' || $role === 'hr_manager';
        $isDepartmentHead = $role === 'department_head';

        if (!$isAdmin && !$isDepartmentHead) {
            return Response::redirect('/vacations');
        }

        $body = $request->getParsedBody() ?? [];
        $reason = $body['reason'] ?? 'Rechazado por el administrador';
        try {
            $approverEmployeeId = $this->getCurrentEmployeeId();
            if (!$approverEmployeeId) {
                throw new Exception('No se pudo identificar al aprobador');
            }

            $this->vacationService->reject($id, $approverEmployeeId, $reason);

            if ($request->isJson()) {
                return Response::json(['success' => true]);
            }

            return Response::redirect('/vacations');
        } catch (Exception $e) {
            if ($request->isJson()) {
                return Response::json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            return Response::redirect('/vacations');
        }
    }

    public function pendingAjax(Request $request): Response
    {
        $this->requireAuth();
        $role = $_SESSION['user_role'] ?? 'employee';
        $isAdmin = $role === 'admin' || $role === 'hr_manager';
        $isDepartmentHead = $role === 'department_head';

        if (!$isAdmin && !$isDepartmentHead) {
            return Response::json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $pendingName = trim((string)$request->getQuery('pending_name', ''));
        $pendingEmail = trim((string)$request->getQuery('pending_email', ''));

        if ($isDepartmentHead) {
            $departmentId = $this->getCurrentEmployeeDepartmentId();
            $pendingApprovals = $this->vacationService->getPendingManagerRequests(
                $departmentId,
                $pendingName,
                $pendingEmail
            );
        } else {
            $pendingApprovals = $this->vacationService->getPendingHRRequests(
                $pendingName,
                $pendingEmail
            );
        }

        $data = array_map(fn($item) => $item->toArray(), $pendingApprovals);

        return Response::json(['success' => true, 'data' => $data]);
    }

    public function historyAjax(Request $request): Response
    {
        $this->requireAuth();
        $role = $_SESSION['user_role'] ?? 'employee';
        $isAdmin = $role === 'admin' || $role === 'hr_manager';
        $isDepartmentHead = $role === 'department_head';

        if (!$isAdmin && !$isDepartmentHead) {
            return Response::json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $historyName = trim((string)$request->getQuery('history_name', ''));
        $historyEmail = trim((string)$request->getQuery('history_email', ''));

        $departmentId = $isDepartmentHead ? $this->getCurrentEmployeeDepartmentId() : null;
        $historyApprovals = $this->vacationService->getRequestsHistory(
            $departmentId,
            $historyName,
            $historyEmail
        );

        $data = array_map(fn($item) => $item->toArray(), $historyApprovals);

        return Response::json(['success' => true, 'data' => $data]);
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    private function getCurrentEmployeeId(): ?string
    {
        if (isset($_SESSION['employee_id'])) {
            return (string)$_SESSION['employee_id'];
        }

        $stmt = $this->db->prepare("SELECT id FROM employees WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $id = $stmt->fetchColumn();

        if ($id) {
            $_SESSION['employee_id'] = $id;
            return (string)$id;
        }
        return null;
    }

    private function getCurrentEmployeeDepartmentId(): ?string
    {
        if (isset($_SESSION['employee_department_id'])) {
            return (string)$_SESSION['employee_department_id'];
        }

        $stmt = $this->db->prepare("SELECT department_id FROM employees WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $departmentId = $stmt->fetchColumn();

        if ($departmentId) {
            $_SESSION['employee_department_id'] = $departmentId;
            return (string)$departmentId;
        }

        return null;
    }
}
