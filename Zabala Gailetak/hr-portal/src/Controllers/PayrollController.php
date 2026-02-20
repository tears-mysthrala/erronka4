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
     * Get my payslips
     * GET /api/payslips/my
     */
    public function getMyPayslips(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $employeeId = $user['id'];

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
     * List all payslips (admin only) with pagination
     */
    private function listAll(Request $request): Response
    {
        $year = $request->getQuery('year');
        $month = $request->getQuery('month');
        
        // Pagination parameters
        $page = (int) ($request->getQuery('page', 1));
        $limit = (int) ($request->getQuery('limit', 20));
        
        // Validate pagination
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get total count for pagination metadata
        $countQuery = 'SELECT COUNT(*) FROM payroll p JOIN employees e ON p.employee_id = e.id WHERE 1=1';
        $countParams = [];

        if ($year) {
            $countQuery .= ' AND EXTRACT(YEAR FROM p.period_start) = ?';
            $countParams[] = (int) $year;
        }

        if ($month) {
            $countQuery .= ' AND EXTRACT(MONTH FROM p.period_start) = ?';
            $countParams[] = (int) $month;
        }

        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($countParams);
        $totalCount = (int) $countStmt->fetchColumn();

        // Get paginated data
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
        $query .= ' LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;

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
            'count' => count($payslips),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalCount,
                'total_pages' => (int) ceil($totalCount / $limit)
            ]
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

            // Get the last inserted ID (MySQL compatible)
            $payslipId = $this->db->lastInsertId();
            
            // Fetch the created record
            $stmt = $this->db->prepare('SELECT * FROM payroll WHERE id = ?');
            $stmt->execute([$payslipId]);
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
        $query = 'UPDATE payroll SET ' . implode(', ', $updates) . ' WHERE id = ?';

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        // Fetch the updated record (MySQL compatible)
        $stmt = $this->db->prepare('SELECT * FROM payroll WHERE id = ?');
        $stmt->execute([$id]);
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

        // Check if payslip exists first (MySQL compatible - no RETURNING)
        $stmt = $this->db->prepare('SELECT id FROM payroll WHERE id = ?');
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            return Response::json(['error' => 'Payslip not found'], 404);
        }

        $stmt = $this->db->prepare('DELETE FROM payroll WHERE id = ?');
        $stmt->execute([$id]);

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

        $query = 'SELECT p.*, e.first_name, e.last_name, e.email, e.department 
                  FROM payroll p 
                  JOIN employees e ON p.employee_id = e.id 
                  WHERE p.id = ?';
        $params = [$id];

        // Regular users can only download their own payslips
        if ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager') {
            $query .= ' AND p.employee_id = ?';
            $params[] = $user['id'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return Response::json(['error' => 'Payslip not found'], 404);
        }

        // Generate PDF using TCPDF
        try {
            $pdfContent = $this->generatePayslipPDF($row);
            
            $filename = sprintf(
                'nomina_%s_%s.pdf',
                $row['last_name'] . '_' . $row['first_name'],
                date('Y-m', strtotime($row['period_start']))
            );
            
            return Response::pdf($pdfContent, $filename);
        } catch (\Exception $e) {
            error_log("PDF Generation Error: " . $e->getMessage());
            return Response::json([
                'error' => 'Error generating PDF. Please try again later.'
            ], 500);
        }
    }
    
    /**
     * Generate payslip PDF content
     * 
     * @param array $payslipData Payslip data from database
     * @return string PDF binary content
     * @throws \Exception If PDF library not available
     */
    private function generatePayslipPDF(array $payslipData): string
    {
        // Check if TCPDF is available in various locations
        $tcpdfPaths = [
            __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php',
            __DIR__ . '/../../libs/tcpdf/tcpdf.php',
            __DIR__ . '/../../libs/tcpdf/TCPDF-main/tcpdf.php',
        ];
        
        $tcpdfPath = null;
        foreach ($tcpdfPaths as $path) {
            if (file_exists($path)) {
                $tcpdfPath = $path;
                break;
            }
        }
        
        if ($tcpdfPath === null) {
            throw new \Exception('TCPDF library not found. Please install it via: composer require tecnickcom/tcpdf or download to libs/tcpdf/');
        }
        
        require_once $tcpdfPath;
        
        // Create new PDF document
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Zabala Gailetak HR Portal');
        $pdf->SetAuthor('Zabala Gailetak');
        $pdf->SetTitle('Nomina - ' . date('Y/m', strtotime($payslipData['period_start'])));
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Company header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'ZABALA GAILETAK, S.L.', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Galleta Fabrika - Herrera de las Gañorras', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Payslip title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'ORDAINSLIP / NÓMINA', 0, 1, 'C');
        $pdf->Ln(5);
        
        // Employee info
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(40, 7, 'Langilea:', 0, 0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 7, $payslipData['first_name'] . ' ' . $payslipData['last_name'], 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(40, 7, 'Email:', 0, 0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 7, $payslipData['email'], 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(40, 7, 'Aldia:', 0, 0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(0, 7, date('Y/m', strtotime($payslipData['period_start'])), 0, 1);
        $pdf->Ln(10);
        
        // Separator line
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);
        
        // Earnings section
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 8, 'SARRERAK / INGRESOS', 0, 1, 'L', true);
        $pdf->Ln(2);
        
        $pdf->SetFont('helvetica', '', 10);
        $this->addPayslipRow($pdf, 'Oinarrizko soldata / Sueldo base:', $payslipData['base_salary']);
        
        if (!empty($payslipData['extra_hours']) && $payslipData['extra_hours'] > 0) {
            $this->addPayslipRow($pdf, 'Ordu extra / Horas extra:', $payslipData['extra_hours']);
        }
        
        if (!empty($payslipData['bonuses']) && $payslipData['bonuses'] > 0) {
            $this->addPayslipRow($pdf, 'Bonusak / Bonificaciones:', $payslipData['bonuses']);
        }
        
        if (!empty($payslipData['commissions']) && $payslipData['commissions'] > 0) {
            $this->addPayslipRow($pdf, 'Komisioak / Comisiones:', $payslipData['commissions']);
        }
        
        $pdf->Ln(5);
        
        // Deductions section
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 8, 'GALTZERDIKETAK / DEDUCCIONES', 0, 1, 'L', true);
        $pdf->Ln(2);
        
        $pdf->SetFont('helvetica', '', 10);
        $this->addPayslipRow($pdf, 'Zergak / Impuestos:', -$payslipData['taxes']);
        $this->addPayslipRow($pdf, 'Gizarte Segurantza / Seguridad Social:', -$payslipData['social_security']);
        
        if (!empty($payslipData['deductions']) && $payslipData['deductions'] > 0) {
            $this->addPayslipRow($pdf, 'Beste galduerak / Otras deducciones:', -$payslipData['deductions']);
        }
        
        if (!empty($payslipData['other_deductions']) && $payslipData['other_deductions'] > 0) {
            $this->addPayslipRow($pdf, 'Gehigarriak / Otros:', -$payslipData['other_deductions']);
        }
        
        $pdf->Ln(10);
        
        // Separator line
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);
        
        // Net salary
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetFillColor(200, 230, 200);
        $pdf->Cell(80, 12, 'GARBISOLDATA / NETO:', 0, 0, 'L', true);
        $pdf->Cell(0, 12, number_format($payslipData['net_salary'], 2) . ' €', 0, 1, 'R', true);
        
        // Notes
        if (!empty($payslipData['notes'])) {
            $pdf->Ln(10);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7, 'Oharrak / Notas:', 0, 1);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->MultiCell(0, 5, $payslipData['notes'], 0, 'L');
        }
        
        // Footer
        $pdf->Ln(15);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(128, 128, 128);
        $pdf->Cell(0, 5, 'Dokumentu hau elektronikoki sortua izan da / Documento generado electrónicamente', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Zabala Gailetak HR Portal - ' . date('Y-m-d H:i:s'), 0, 1, 'C');
        
        // Output PDF as string
        return $pdf->Output('', 'S');
    }
    
    /**
     * Add a row to the payslip PDF
     */
    private function addPayslipRow(\TCPDF $pdf, string $label, float $amount): void
    {
        $pdf->Cell(120, 6, $label, 0, 0);
        $pdf->Cell(0, 6, number_format(abs($amount), 2) . ' €', 0, 1, 'R');
    }
}
