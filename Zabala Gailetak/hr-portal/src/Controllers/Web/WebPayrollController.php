<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers\Web;

use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Models\Payslip;

/**
 * Web Payroll Controller
 * Handles server-side rendered pages for payroll
 */
class WebPayrollController
{
    public function __construct(
        private readonly Database $db
    ) {
    }

    /**
     * List payslips view
     * GET /payslips
     */
    public function index(Request $request): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return Response::redirect('/login');
        }

        $employeeId = $user['id'];
        $year = $request->getQuery('year') ?? date('Y');
        $month = $request->getQuery('month');
        
        // Get payslips
        $query = 'SELECT p.*, e.first_name, e.last_name 
                  FROM payroll p 
                  LEFT JOIN employees e ON p.employee_id = e.id
                  WHERE 1=1';
        $params = [];
        
        // Admin can view all, employees only their own
        if ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR') {
            $query .= ' AND p.employee_id = ?';
            $params[] = $employeeId;
        }
        
        if ($year) {
            $query .= ' AND EXTRACT(YEAR FROM p.period_start) = ?';
            $params[] = (int) $year;
        }
        
        if ($month) {
            $query .= ' AND EXTRACT(MONTH FROM p.period_start) = ?';
            $params[] = (int) $month;
        }
        
        $query .= ' ORDER BY p.period_start DESC';
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $payslips = array_map(function ($row) {
            $payslip = Payslip::fromDatabase($row);
            $data = $payslip->toArray();
            if (isset($row['first_name'])) {
                $data['employee_name'] = trim($row['first_name'] . ' ' . $row['last_name']);
            }
            return $data;
        }, $rows);
        
        // Get available years
        $stmt = $this->db->query(
            'SELECT DISTINCT EXTRACT(YEAR FROM period_start) as year 
             FROM payroll 
             ORDER BY year DESC'
        );
        $years = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        // Calculate summary statistics
        $summary = null;
        if (!empty($payslips)) {
            $summary = [
                'total_count' => count($payslips),
                'ytd_gross' => array_sum(array_column($payslips, 'gross_salary')),
                'ytd_net' => array_sum(array_column($payslips, 'net_salary')),
                'latest_payslip' => $payslips[0] ?? null
            ];
        }
        
        return $this->renderView('payslips/index', [
            'user' => $user,
            'payslips' => $payslips,
            'years' => $years,
            'selected_year' => $year,
            'selected_month' => $month,
            'summary' => $summary
        ]);
    }

    /**
     * Show payslip detail
     * GET /payslips/{id}
     */
    public function show(Request $request, string $id): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return Response::redirect('/login');
        }

        $query = 'SELECT p.*, e.first_name, e.last_name, e.email 
                  FROM payroll p 
                  LEFT JOIN employees e ON p.employee_id = e.id
                  WHERE p.id = ?';
        $params = [$id];
        
        // Regular users can only view their own payslips
        if ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR') {
            $query .= ' AND p.employee_id = ?';
            $params[] = $user['id'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$row) {
            $_SESSION['flash_error'] = 'Nomina no encontrada';
            return Response::redirect('/payslips');
        }
        
        $payslip = Payslip::fromDatabase($row);
        $payslipData = $payslip->toArray();
        $payslipData['employee_name'] = trim($row['first_name'] . ' ' . $row['last_name']);
        $payslipData['employee_email'] = $row['email'];
        
        return $this->renderView('payslips/show', [
            'user' => $user,
            'payslip' => $payslipData
        ]);
    }

    /**
     * Create payslip form (Admin only)
     * GET /payslips/create
     */
    public function createForm(Request $request): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user || ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR')) {
            $_SESSION['flash_error'] = 'Access denied';
            return Response::redirect('/dashboard');
        }

        // Get employees for dropdown
        $stmt = $this->db->query(
            'SELECT e.id, e.first_name, e.last_name, u.email 
             FROM employees e 
             JOIN users u ON e.user_id = u.id 
             WHERE e.is_active = TRUE
             ORDER BY e.last_name, e.first_name'
        );
        $employees = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $this->renderView('payslips/create', [
            'user' => $user,
            'employees' => $employees
        ]);
    }

    /**
     * Create payslip (Admin only)
     * POST /payslips/create
     */
    public function create(Request $request): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user || ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR')) {
            $_SESSION['flash_error'] = 'Access denied';
            return Response::redirect('/dashboard');
        }

        $data = $request->getParsedBody();
        
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO payroll (
                    employee_id, period_start, period_end, base_salary,
                    extra_hours, bonuses, commissions, deductions,
                    taxes, social_security, other_deductions, net_salary, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            
            $stmt->execute([
                $data['employee_id'],
                $data['period_start'],
                $data['period_end'],
                $data['base_salary'],
                $data['extra_hours'] ?? 0,
                $data['bonuses'] ?? 0,
                $data['commissions'] ?? 0,
                $data['deductions'] ?? 0,
                $data['taxes'] ?? 0,
                $data['social_security'] ?? 0,
                $data['other_deductions'] ?? 0,
                $data['net_salary'],
                $data['notes'] ?? null
            ]);
            
            $_SESSION['flash_success'] = 'Nomina creada correctamente';
            return Response::redirect('/payslips');
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = 'Error al crear nomina: ' . $e->getMessage();
            return Response::redirect('/payslips/create');
        }
    }

    /**
     * Render a view template
     */
    private function renderView(string $view, array $data = []): Response
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../../../public/views/' . $view . '.php';
        $content = ob_get_clean();
        return new Response($content);
    }
}
