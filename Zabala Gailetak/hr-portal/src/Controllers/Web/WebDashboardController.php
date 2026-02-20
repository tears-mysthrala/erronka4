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

    /**
     * Get authenticated user from session or JWT
     */
    private function getUser(Request $request): ?array
    {
        // First check session (web login) - supports both array and individual keys
        if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        
        // Check individual session keys (set by WebAuthController)
        if (isset($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'employee',
                'name' => $_SESSION['user_name'] ?? ''
            ];
        }
        
        // Then check JWT (API login via request attribute set by AuthenticationMiddleware)
        $jwtUser = $request->getAttribute('user');
        if ($jwtUser) {
            return $jwtUser;
        }
        
        return null;
    }

    public function index(Request $request): Response
    {
        // Check authentication without redirecting (middleware should handle this)
        $user = $this->getUser($request);
        if (!$user) {
            return Response::redirect('/login');
        }

        // Get basic stats
        $employeeCount = (int)$this->db->query("SELECT COUNT(*) FROM employees WHERE is_active = true")->fetchColumn();
        $pendingVacations = (int)$this->db->query("SELECT COUNT(*) FROM vacation_requests WHERE status = 'PENDING'")->fetchColumn();

        // Get upcoming vacations (next 30 days) - compatible with MySQL/MariaDB
        $stmt = $this->db->query("
            SELECT e.first_name, e.last_name, vr.start_date, vr.end_date
            FROM vacation_requests vr
            JOIN employees e ON vr.employee_id = e.id
            WHERE vr.status = 'APPROVED' 
              AND vr.start_date >= CURRENT_DATE 
              AND vr.start_date <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)
            ORDER BY vr.start_date ASC
            LIMIT 5
        ");
        $upcomingVacations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Response::view('dashboard/index', [
            'user' => $user['email'] ?? 'Usuario',
            'role' => $user['role'] ?? 'employee',
            'stats' => [
                'employees' => $employeeCount,
                'pending_vacations' => $pendingVacations
            ],
            'upcomingVacations' => $upcomingVacations
        ]);
    }
}
