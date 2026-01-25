<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers\Web;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Validation\EmployeeValidator;
use ZabalaGailetak\HrPortal\Services\AuditLogger;
use PDO;
use Exception;

class WebEmployeeController
{
    private Database $db;
    private EmployeeValidator $validator;
    private AuditLogger $auditLogger;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->validator = new EmployeeValidator();
        $this->auditLogger = new AuditLogger($db);
    }

    public function index(Request $request): Response
    {
        $this->requireAuth();

        $page = (int)($request->getQuery('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT 
                e.id, e.employee_number, e.first_name, e.last_name, e.position, e.is_active,
                d.name as department_name, u.email
            FROM employees e
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            ORDER BY e.last_name ASC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $this->db->query("SELECT COUNT(*) FROM employees");
        $total = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($total / $limit);

        return Response::view('employees/index', [
            'employees' => $employees,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function show(Request $request, string $id): Response
    {
        $this->requireAuth();

        $sql = "SELECT e.*, u.email, u.role, d.name as department_name
                FROM employees e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return Response::redirect('/employees');
        }

        return Response::view('employees/show', ['employee' => $employee]);
    }

    public function createForm(Request $request): Response
    {
        $this->requireAuth();
        $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Response::view('employees/create', [
            'departments' => $departments,
            'errors' => [],
            'old' => []
        ]);
    }

    public function create(Request $request): Response
    {
        $this->requireAuth();
        $data = $request->getParsedBody();
        $data = $this->validator->sanitizeData($data);

        $errors = $this->validator->validate($data, false);
        if (!empty($errors)) {
            $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
            return Response::view('employees/create', [
                'departments' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'errors' => $errors,
                'old' => $data
            ]);
        }

        try {
            $this->db->beginTransaction();

            // 1. Create User
            $sql = "INSERT INTO users (email, password_hash, role) VALUES (:email, :password, :role) RETURNING id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'role' => $data['role'] ?? 'employee'
            ]);
            $userId = $stmt->fetchColumn();

            // 2. Create Employee
            $empNum = $this->generateEmployeeNumber();
            $stmt = $this->db->prepare("
                INSERT INTO employees (
                    user_id, employee_number, first_name, last_name, nif, 
                    position, department_id, hire_date, salary, is_active
                ) VALUES (
                    :user_id, :emp_num, :first_name, :last_name, :nif, 
                    :position, :dept_id, :hire_date, :salary, true
                ) RETURNING id
            ");
            $stmt->execute([
                'user_id' => $userId,
                'emp_num' => $empNum,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'nif' => $data['nif'],
                'position' => $data['position'] ?? '',
                'dept_id' => $data['department_id'] ?: null,
                'hire_date' => $data['hire_date'] ?: date('Y-m-d'),
                'salary' => $data['salary'] ?: 0
            ]);
            $employeeId = $stmt->fetchColumn();

            $this->db->commit();

            $this->auditLogger->logCreate(
                entityType: 'employee',
                entityId: (string)$employeeId,
                newValues: $data,
                userId: $_SESSION['user_id'] ?? 'system',
                userEmail: $_SESSION['user_email'] ?? 'system',
                userRole: $_SESSION['user_role'] ?? 'admin'
            );

            return Response::redirect('/employees');
        } catch (Exception $e) {
            $this->db->rollback();
            error_log($e->getMessage());
            return Response::view('employees/create', [
                'departments' => [], // Should refetch
                'errors' => ['system' => 'Error al crear el empleado: ' . $e->getMessage()],
                'old' => $data
            ]);
        }
    }

    public function editForm(Request $request, string $id): Response
    {
        $this->requireAuth();

        $sql = "SELECT e.*, u.email, u.role FROM employees e JOIN users u ON e.user_id = u.id WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return Response::redirect('/employees');
        }

        $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Response::view('employees/edit', [
            'employee' => $employee,
            'departments' => $departments,
            'errors' => []
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $this->requireAuth();
        $data = $request->getParsedBody();
        $data = $this->validator->sanitizeData($data);

        $errors = $this->validator->validate($data, true);
        if (!empty($errors)) {
             // Handle redirect back with errors
        }

        try {
            $sql = "UPDATE employees SET 
                    first_name = :first_name, last_name = :last_name, 
                    nif = :nif, position = :position, department_id = :dept_id,
                    hire_date = :hire_date, salary = :salary, is_active = :active
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'nif' => $data['nif'],
                'position' => $data['position'],
                'dept_id' => $data['department_id'] ?: null,
                'hire_date' => $data['hire_date'],
                'salary' => $data['salary'],
                'active' => isset($data['active']) ? 1 : 0,
                'id' => $id
            ]);

            return Response::redirect("/employees/show/$id");
        } catch (Exception $e) {
            return Response::redirect('/employees');
        }
    }

    public function delete(Request $request, string $id): Response
    {
        $this->requireAuth();
        // Soft delete
        $stmt = $this->db->prepare("UPDATE employees SET is_active = false WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return Response::redirect('/employees');
    }

    public function export(Request $request): Response
    {
        $this->requireAuth();
        $stmt = $this->db->query("SELECT * FROM employees");
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys($employees[0] ?? []));
        foreach ($employees as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        $csv = ob_get_clean();

        return new Response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees.csv"'
        ]);
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    private function generateEmployeeNumber(): string
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM employees WHERE hire_date LIKE :year");
        $stmt->execute(['year' => "$year%"]);
        $count = (int)$stmt->fetchColumn() + 1;
        return "EMP-{$year}-" . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
    }
}
