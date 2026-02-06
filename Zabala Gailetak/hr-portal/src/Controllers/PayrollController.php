<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers;

use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Models\Payslip;

/**
 * Payroll Controller
 * Handles payroll/payslip operations
 */
class PayrollController
{
    public function __construct(
        private readonly Database $db
    ) {
    }

    /**
     * List employee's payslips
     * GET /api/payroll
     */
    public function index(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $employeeId = $user['id'];
        
        // Admin can view all payslips
        if ($user['role'] === 'admin' || $user['role'] === 'hr_manager') {
            $requestedEmployeeId = $request->getQuery('employee_id');
            if ($requestedEmployeeId) {
                $employeeId = $requestedEmployeeId;
            } else {
                // Return all payslips for admin
                return $this->listAll($request);
            }
        }

        // Filters
        $year = $request->getQuery('year');
        $month = $request->getQuery('month');
        
        $query = 'SELECT * FROM payroll WHERE employee_id = ?';
        $params = [$employeeId];
        
        if ($year) {
            $query .= ' AND EXTRACT(YEAR FROM period_start) = ?';
            $params[] = (int) $year;
        }
        
        if ($month) {
            $query .= ' AND EXTRACT(MONTH FROM period_start) = ?';
            $params[] = (int) $month;
        }
        
        $query .= ' ORDER BY period_start DESC';
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $payslips = array_map(fn($row) => Payslip::fromDatabase($row)->toArray(), $rows);
        
        return Response::json([
            'success' => true,
            'data' => $payslips,
            'count' => count($payslips)
        ]);
    }

    /**
     * List all payslips (admin only)
     */
    private function listAll(Request $request): Response
    {
        $year = $request->getQuery('year');
        $month = $request->getQuery('month');
        
        $query = 'SELECT p.*, e.first_name, e.last_name, e.email 
                  FROM payroll p 
                  JOIN employees e ON p.employee_id = e.id
                  WHERE 1=1';
        $params = [];
        
        if ($year) {
            $query .= ' AND EXTRACT(YEAR FROM p.period_start) = ?';
            $params[] = (int) $year;
        }
        
        if ($month) {
            $query .= ' AND EXTRACT(MONTH FROM p.period_start) = ?';
            $params[] = (int) $month;
        }
        
        $query .= ' ORDER BY p.period_start DESC, e.last_name ASC';
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $payslips = array_map(function ($row) {
            $payslip = Payslip::fromDatabase($row);
            $data = $payslip->toArray();
            $data['employee_name'] = trim($row['first_name'] . ' ' . $row['last_name']);
            $data['employee_email'] = $row['email'];
            return $data;
        }, $rows);
        
        return Response::json([
            'success' => true,
            'data' => $payslips,
            'count' => count($payslips)
        ]);
    }

    /**
     * Get specific payslip details
     * GET /api/payroll/{id}
     */
    public function show(Request $request, string $id): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $query = 'SELECT * FROM payroll WHERE id = ?';
        $params = [$id];
        
        // Regular users can only view their own payslips
        if ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager') {
            $query .= ' AND employee_id = ?';
            $params[] = $user['id'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$row) {
            return Response::json(['error' => 'Payslip not found'], 404);
        }
        
        $payslip = Payslip::fromDatabase($row);
        
        return Response::json([
            'success' => true,
            'data' => $payslip->toArray()
        ]);
    }

    /**
     * Create new payslip (Admin only)
     * POST /api/payroll
     */
    public function create(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager')) {
            return Response::json(['error' => 'Forbidden'], 403);
        }

        $data = $request->getParsedBody();
        
        // Validate required fields
        $required = ['employee_id', 'period_start', 'period_end', 'base_salary', 'net_salary'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return Response::json(['error' => "Missing required field: $field"], 400);
            }
        }
        
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO payroll (
                    employee_id, period_start, period_end, base_salary,
                    extra_hours, bonuses, commissions, deductions,
                    taxes, social_security, other_deductions, net_salary, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                RETURNING *'
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
            
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $payslip = Payslip::fromDatabase($row);
            
            return Response::json([
                'success' => true,
                'message' => 'Payslip created successfully',
                'data' => $payslip->toArray()
            ], 201);
        } catch (\PDOException $e) {
            if (str_contains($e->getMessage(), 'duplicate key')) {
                return Response::json([
                    'error' => 'Payslip for this period already exists'
                ], 409);
            }
            throw $e;
        }
    }

    /**
     * Update payslip (Admin only)
     * PUT /api/payroll/{id}
     */
    public function update(Request $request, string $id): Response
    {
        $user = $request->getAttribute('user');
        if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager')) {
            return Response::json(['error' => 'Forbidden'], 403);
        }

        $data = $request->getParsedBody();
        
        // Check if payslip exists
        $stmt = $this->db->prepare('SELECT id FROM payroll WHERE id = ?');
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            return Response::json(['error' => 'Payslip not found'], 404);
        }
        
        // Build update query dynamically
        $updates = [];
        $params = [];
        
        $allowedFields = [
            'base_salary', 'extra_hours', 'bonuses', 'commissions',
            'deductions', 'taxes', 'social_security', 'other_deductions',
            'net_salary', 'notes'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($updates)) {
            return Response::json(['error' => 'No fields to update'], 400);
        }
        
        $params[] = $id;
        $query = 'UPDATE payroll SET ' . implode(', ', $updates) . ' WHERE id = ? RETURNING *';
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $payslip = Payslip::fromDatabase($row);
        
        return Response::json([
            'success' => true,
            'message' => 'Payslip updated successfully',
            'data' => $payslip->toArray()
        ]);
    }

    /**
     * Delete payslip (Admin only)
     * DELETE /api/payroll/{id}
     */
    public function delete(Request $request, string $id): Response
    {
        $user = $request->getAttribute('user');
        if (!$user || $user['role'] !== 'admin') {
            return Response::json(['error' => 'Forbidden'], 403);
        }

        $stmt = $this->db->prepare('DELETE FROM payroll WHERE id = ? RETURNING id');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if (!$row) {
            return Response::json(['error' => 'Payslip not found'], 404);
        }
        
        return Response::json([
            'success' => true,
            'message' => 'Payslip deleted successfully'
        ]);
    }

    /**
     * Download payslip PDF
     * GET /api/payroll/{id}/download
     */
    public function download(Request $request, string $id): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $query = 'SELECT * FROM payroll WHERE id = ?';
        $params = [$id];
        
        // Regular users can only download their own payslips
        if ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager') {
            $query .= ' AND employee_id = ?';
            $params[] = $user['id'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$row) {
            return Response::json(['error' => 'Payslip not found'], 404);
        }
        
        // TODO: Generate PDF if not exists
        // For now, return success message
        return Response::json([
            'success' => true,
            'message' => 'PDF generation will be implemented',
            'data' => [
                'id' => $id,
                'filename' => 'payslip_' . date('Y-m', strtotime($row['period_start'])) . '.pdf'
            ]
        ]);
    }
}
