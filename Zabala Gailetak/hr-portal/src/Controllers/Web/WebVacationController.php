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

        // If admin, also get pending requests
        $pendingApprovals = [];
        if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'hr_manager') {
            $pendingApprovals = $this->vacationService->getPendingManagerRequests();
        }

        return Response::view('vacations/index', [
            'balance' => $balance,
            'requests' => $requests,
            'pendingApprovals' => $pendingApprovals,
            'year' => $year
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
            return Response::redirect('/vacations');
        }

        try {
            $this->vacationService->createRequest(
                $employeeId,
                $data['start_date'],
                $data['end_date'],
                $data['notes'] ?? null
            );
            return Response::redirect('/vacations');
        } catch (Exception $e) {
            return Response::view('vacations/create', ['errors' => ['system' => $e->getMessage()]]);
        }
    }

    public function approve(Request $request, string $id): Response
    {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'hr_manager') {
            return Response::redirect('/vacations');
        }

        try {
            $this->vacationService->approveByHR($id, $_SESSION['user_id'], "Aprobado desde panel web");
            return Response::redirect('/vacations');
        } catch (Exception $e) {
            return Response::redirect('/vacations');
        }
    }

    public function reject(Request $request, string $id): Response
    {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'hr_manager') {
            return Response::redirect('/vacations');
        }

        $reason = $request->getParsedBody()['reason'] ?? 'Rechazado por el administrador';
        try {
            $this->vacationService->reject($id, $_SESSION['user_id'], $reason);
            return Response::redirect('/vacations');
        } catch (Exception $e) {
            return Response::redirect('/vacations');
        }
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
}
