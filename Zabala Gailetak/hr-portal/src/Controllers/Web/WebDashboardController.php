<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers\Web;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Database\Database;
use PDO;

class WebDashboardController
{
    private Database $db;

    public function __construct()
    {
        $this->db = $GLOBALS['app']->getDatabase();
    }

    public function index(Request $request): Response
    {
        $this->requireAuth();

        // Get basic stats
        $employeeCount = (int)$this->db->query("SELECT COUNT(*) FROM employees WHERE is_active = true")->fetchColumn();
        $pendingVacations = (int)$this->db->query("SELECT COUNT(*) FROM vacation_requests WHERE status = 'PENDING'")->fetchColumn();
        
        // Get upcoming vacations (next 30 days)
        $stmt = $this->db->query("
            SELECT e.first_name, e.last_name, vr.start_date, vr.end_date
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.status = 'APPROVED' 
              AND vr.start_date >= CURRENT_DATE 
              AND vr.start_date <= CURRENT_DATE + INTERVAL '30 days'
            ORDER BY vr.start_date ASC
            LIMIT 5
        ");
        $upcomingVacations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Response::view('dashboard/index', [
            'user' => $_SESSION['user_email'] ?? 'Usuario',
            'role' => $_SESSION['user_role'] ?? 'employee',
            'stats' => [
                'employees' => $employeeCount,
                'pending_vacations' => $pendingApprovals ?? $pendingVacations
            ],
            'upcomingVacations' => $upcomingVacations
        ]);
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}
