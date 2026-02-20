<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers\Web;

use ZabalaGailetak\HrPortal\Auth\AccessControl;
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
    private AccessControl $accessControl;
    private EmployeeValidator $validator;
    private AuditLogger $auditLogger;

    public function __construct(Database $db, ?AccessControl $accessControl = null)
    {
        $this->db = $db;
        $this->accessControl = $accessControl ?? new AccessControl();
        $this->validator = new EmployeeValidator();
        $this->auditLogger = new AuditLogger($db);
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

    /**
     * Extraer userId y role desde sesión o JWT
     */
    private function getAuthInfo(Request $request): ?array
    {
        $user = $this->getUser($request);
        if (!$user) {
            return null;
        }

        return [
            'user_id' => $user['id'] ?? $user['user_id'] ?? null,
            'role' => $user['role'] ?? null,
            'email' => $user['email'] ?? ''
        ];
    }

    public function index(Request $request): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        if (!$this->accessControl->hasPermission($auth['role'], 'employees.view_all')) {
            return Response::view('errors/403', ['message' => 'No tienes permisos para ver todos los empleados']);
        }

        $page = (int)($request->getQuery('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Construir query base con permisos
        $whereConditions = [];
        $params = [];

        // Si es jefe de sección, solo ve su departamento
        if ($auth['role'] === 'department_head') {
            // Obtener departamento del jefe
            $stmt = $this->db->prepare(
                'SELECT e.department_id 
                FROM employees e 
                WHERE e.user_id = :user_id'
            );
            $stmt->execute(['user_id' => $auth['user_id']]);
            $deptId = $stmt->fetchColumn();

            if ($deptId) {
                $whereConditions[] = 'e.department_id = :dept_id';
                $params['dept_id'] = $deptId;
            }
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        $sql = "SELECT 
                e.id, e.employee_number, e.first_name, e.last_name, e.position, e.is_active,
                d.name as department_name, u.email, u.role
            FROM employees e
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            $whereClause
            ORDER BY e.last_name ASC
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contar total con mismos filtros
        $countSql = "SELECT COUNT(*) 
            FROM employees e
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            $whereClause";
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue(":$key", $value);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($total / $limit);

        return Response::view('employees/index', [
            'employees' => $employees,
            'page' => $page,
            'totalPages' => $totalPages,
            'auth' => $auth
        ]);
    }

    public function show(Request $request, string $id): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        if (!$this->accessControl->hasPermission($auth['role'], 'employees.view')) {
            return Response::view('errors/403', ['message' => 'No tienes permisos para ver empleados']);
        }

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

        // Verificar permisos específicos
        if ($auth['role'] === 'department_head') {
            // Jefe de sección solo puede ver su departamento
            $stmt = $this->db->prepare(
                'SELECT department_id FROM employees WHERE user_id = :user_id'
            );
            $stmt->execute(['user_id' => $auth['user_id']]);
            $userDeptId = $stmt->fetchColumn();

            if ($userDeptId != $employee['department_id']) {
                return Response::view('errors/403', ['message' => 'No tienes permisos para ver este empleado']);
            }
        } elseif ($auth['role'] === 'employee') {
            // Empleado solo puede ver su propio perfil
            $stmt = $this->db->prepare(
                'SELECT id FROM employees WHERE user_id = :user_id AND id = :id'
            );
            $stmt->execute(['user_id' => $auth['user_id'], 'id' => $id]);
            if (!$stmt->fetch()) {
                return Response::view('errors/403', ['message' => 'No tienes permisos para ver este empleado']);
            }
        }

        return Response::view('employees/show', [
            'employee' => $employee,
            'auth' => $auth
        ]);
    }

    public function createForm(Request $request): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        if (!$this->accessControl->hasPermission($auth['role'], 'employees.create')) {
            return Response::view('errors/403', ['message' => 'No tienes permisos para crear empleados']);
        }

        $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Response::view('employees/create', [
            'departments' => $departments,
            'errors' => [],
            'old' => [],
            'auth' => $auth
        ]);
    }

    public function create(Request $request): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        if (!$this->accessControl->hasPermission($auth['role'], 'employees.create')) {
            return Response::view('errors/403', ['message' => 'No tienes permisos para crear empleados']);
        }

        $data = $request->getParsedBody();
        $data = $this->validator->sanitizeData($data);

        $errors = $this->validator->validate($data, false);
        if (!empty($errors)) {
            $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
            return Response::view('employees/create', [
                'departments' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'errors' => $errors,
                'old' => $data,
                'auth' => $auth
            ]);
        }

        try {
            $this->db->beginTransaction();

            // 1. Create User
            $userRole = $data['role'] ?? 'employee';
            $sql = "INSERT INTO users (email, password_hash, role) VALUES (:email, :password, :role)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'role' => $userRole
            ]);
            $userId = $this->db->lastInsertId();

            // 2. Create Employee
            $empNum = $this->generateEmployeeNumber();
            $stmt = $this->db->prepare("
                INSERT INTO employees (
                    user_id, employee_number, first_name, last_name, nif, 
                    position, department_id, hire_date, salary, is_active
                ) VALUES (
                    :user_id, :emp_num, :first_name, :last_name, :nif, 
                    :position, :dept_id, :hire_date, :salary, true
                )
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
            $employeeId = $this->db->lastInsertId();

            $this->db->commit();

            $this->auditLogger->logCreate(
                entityType: 'employee',
                entityId: (string)$employeeId,
                newValues: array_merge($data, ['employee_number' => $empNum, 'role' => $userRole]),
                userId: $auth['user_id'],
                userEmail: $auth['email'],
                userRole: $auth['role']
            );

            return Response::redirect('/employees');
        } catch (Exception $e) {
            $this->db->rollback();
            error_log($e->getMessage());
            
            $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
            return Response::view('employees/create', [
                'departments' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'errors' => ['system' => 'Error al crear el empleado: ' . $e->getMessage()],
                'old' => $data,
                'auth' => $auth
            ]);
        }
    }

    public function editForm(Request $request, string $id): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        if (!$this->accessControl->hasPermission($auth['role'], 'employees.edit')) {
            // Permitir auto-edición para admin
            if ($auth['role'] === 'admin') {
                // Verificar si está editando su propio perfil
                $stmt = $this->db->prepare(
                    'SELECT e.id FROM employees e WHERE e.user_id = :user_id AND e.id = :id'
                );
                $stmt->execute(['user_id' => $auth['user_id'], 'id' => $id]);
                if (!$stmt->fetch()) {
                    return Response::view('errors/403', ['message' => 'No tienes permisos para editar este empleado']);
                }
            } else {
                return Response::view('errors/403', ['message' => 'No tienes permisos para editar empleados']);
            }
        }

        $sql = "SELECT e.*, u.email, u.role FROM employees e JOIN users u ON e.user_id = u.id WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return Response::redirect('/employees');
        }

        // Verificar permisos específicos si no es admin
        if ($auth['role'] === 'department_head') {
            // Jefe de sección solo puede editar su departamento
            $stmt = $this->db->prepare(
                'SELECT department_id FROM employees WHERE user_id = :user_id'
            );
            $stmt->execute(['user_id' => $auth['user_id']]);
            $userDeptId = $stmt->fetchColumn();

            if ($userDeptId != $employee['department_id']) {
                return Response::view('errors/403', ['message' => 'No tienes permisos para editar este empleado']);
            }
        } elseif ($auth['role'] === 'employee') {
            // Empleado solo puede editar su propio perfil
            $stmt = $this->db->prepare(
                'SELECT id FROM employees WHERE user_id = :user_id AND id = :id'
            );
            $stmt->execute(['user_id' => $auth['user_id'], 'id' => $id]);
            if (!$stmt->fetch()) {
                return Response::view('errors/403', ['message' => 'No tienes permisos para editar este empleado']);
            }
        }

        $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Response::view('employees/edit', [
            'employee' => $employee,
            'departments' => $departments,
            'errors' => [],
            'auth' => $auth
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        $canEdit = $this->accessControl->hasPermission($auth['role'], 'employees.edit');
        $isSelfEdit = false;
        
        if (!$canEdit && $auth['role'] === 'admin') {
            // Permitir auto-edición para admin
            $stmt = $this->db->prepare(
                'SELECT e.id FROM employees e WHERE e.user_id = :user_id AND e.id = :id'
            );
            $stmt->execute(['user_id' => $auth['user_id'], 'id' => $id]);
            $isSelfEdit = $stmt->fetch() !== false;
        }

        if (!$canEdit && !$isSelfEdit) {
            return Response::view('errors/403', ['message' => 'No tienes permisos para editar este empleado']);
        }

        $data = $request->getParsedBody();
        $data = $this->validator->sanitizeData($data);

        $errors = $this->validator->validate($data, true);
        if (!empty($errors)) {
            $sql = "SELECT e.*, u.email, u.role FROM employees e JOIN users u ON e.user_id = u.id WHERE e.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->db->query("SELECT id, name FROM departments WHERE is_active = true ORDER BY name");
            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return Response::view('employees/edit', [
                'employee' => $employee,
                'departments' => $departments,
                'errors' => $errors,
                'old' => $data,
                'auth' => $auth
            ]);
        }

        try {
            // Obtener valores anteriores para auditoría
            $stmt = $this->db->prepare(
                'SELECT e.*, u.email, u.role FROM employees e JOIN users u ON e.user_id = u.id WHERE e.id = :id'
            );
            $stmt->execute(['id' => $id]);
            $oldEmployee = $stmt->fetch(PDO::FETCH_ASSOC);

            // Actualizar empleado
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
                'hire_date' => $data['hire_date'] ?? ($oldEmployee['hire_date'] ?? date('Y-m-d')),
                'salary' => $data['salary'] ?? ($oldEmployee['salary'] ?? 0),
                'active' => isset($data['active']) ? 1 : 0,
                'id' => $id
            ]);

            // Si se actualiza el email, actualizar en users (solo si tiene permisos)
            if (isset($data['email']) && $canEdit) {
                $stmt = $this->db->prepare(
                    'UPDATE users SET email = :email WHERE id = :user_id'
                );
                $stmt->execute([
                    'email' => $data['email'],
                    'user_id' => $oldEmployee['user_id']
                ]);
            }

            // Registrar auditoría
            $this->auditLogger->logUpdate(
                entityType: 'employee',
                entityId: $id,
                oldValues: [
                    'first_name' => $oldEmployee['first_name'],
                    'last_name' => $oldEmployee['last_name'],
                    'nif' => $oldEmployee['nif'],
                    'position' => $oldEmployee['position'],
                    'department_id' => $oldEmployee['department_id'],
                    'hire_date' => $oldEmployee['hire_date'],
                    'salary' => $oldEmployee['salary'],
                    'is_active' => $oldEmployee['is_active'],
                    'email' => $oldEmployee['email']
                ],
                newValues: array_merge($data, ['is_active' => isset($data['active']) ? 1 : 0]),
                userId: $auth['user_id'],
                userEmail: $auth['email'],
                userRole: $auth['role']
            );

            return Response::redirect("/employees/show/$id");
        } catch (Exception $e) {
            error_log($e->getMessage());
            return Response::redirect('/employees');
        }
    }

    public function delete(Request $request, string $id): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        if (!$this->accessControl->hasPermission($auth['role'], 'employees.delete')) {
            return Response::view('errors/403', ['message' => 'No tienes permisos para eliminar empleados']);
        }

        try {
            // Soft delete
            $stmt = $this->db->prepare("UPDATE employees SET is_active = false, updated_at = NOW() WHERE id = :id");
            $stmt->execute(['id' => $id]);

            // Auditoría
            $stmt = $this->db->prepare('SELECT * FROM employees WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->auditLogger->logDelete(
                entityType: 'employee',
                entityId: $id,
                oldValues: [
                    'employee_number' => $employee['employee_number'],
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'is_active' => true
                ],
                userId: $auth['user_id'],
                userEmail: $auth['email'],
                userRole: $auth['role']
            );

            return Response::redirect('/employees');
        } catch (Exception $e) {
            error_log($e->getMessage());
            return Response::redirect('/employees');
        }
    }

    public function export(Request $request): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Verificar permisos
        if (!$this->accessControl->hasPermission($auth['role'], 'employees.view_all')) {
            return Response::view('errors/403', ['message' => 'No tienes permisos para exportar empleados']);
        }

        // Construir query con permisos
        $whereConditions = [];
        $params = [];

        // Si es jefe de sección, solo exporta su departamento
        if ($auth['role'] === 'department_head') {
            $stmt = $this->db->prepare(
                'SELECT e.department_id FROM employees e WHERE e.user_id = :user_id'
            );
            $stmt->execute(['user_id' => $auth['user_id']]);
            $deptId = $stmt->fetchColumn();

            if ($deptId) {
                $whereConditions[] = 'e.department_id = :dept_id';
                $params['dept_id'] = $deptId;
            }
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        $sql = "SELECT 
                e.employee_number, e.first_name, e.last_name, e.nif, e.position,
                e.phone, e.address, e.city, e.postal_code, e.country,
                e.hire_date, e.salary, e.is_active,
                u.email, u.role, d.name as department_name
            FROM employees e
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            $whereClause
            ORDER BY e.last_name, e.first_name";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, [
            'Employee Number', 'First Name', 'Last Name', 'NIF', 'Position',
            'Phone', 'Address', 'City', 'Postal Code', 'Country',
            'Hire Date', 'Salary', 'Active', 'Email', 'Role', 'Department'
        ]);
        foreach ($employees as $row) {
            fputcsv($df, [
                $row['employee_number'],
                $row['first_name'],
                $row['last_name'],
                $row['nif'],
                $row['position'],
                $row['phone'],
                $row['address'],
                $row['city'],
                $row['postal_code'],
                $row['country'],
                $row['hire_date'],
                $row['salary'],
                $row['is_active'] ? 'Yes' : 'No',
                $row['email'],
                $row['role'],
                $row['department_name']
            ]);
        }
        fclose($df);
        $csv = ob_get_clean();

        return new Response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees.csv"'
        ]);
    }

    /**
     * Perfil del usuario autenticado
     */
    public function profile(Request $request): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Obtener empleado del usuario autenticado
        $stmt = $this->db->prepare(
            'SELECT e.*, u.email, u.role, d.name as department_name
             FROM employees e
             JOIN users u ON e.user_id = u.id
             LEFT JOIN departments d ON e.department_id = d.id
             WHERE e.user_id = :user_id'
        );
        $stmt->execute(['user_id' => $auth['user_id']]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return Response::view('errors/404', ['message' => 'Perfil no encontrado']);
        }

        return Response::view('employees/profile', [
            'employee' => $employee,
            'auth' => $auth,
            'canEdit' => true // Siempre puede editar su propio perfil
        ]);
    }

    /**
     * Formulario para editar perfil propio
     */
    public function editProfileForm(Request $request): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Obtener empleado del usuario autenticado
        $stmt = $this->db->prepare(
            'SELECT e.*, u.email, u.role FROM employees e JOIN users u ON e.user_id = u.id WHERE e.user_id = :user_id'
        );
        $stmt->execute(['user_id' => $auth['user_id']]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return Response::view('errors/404', ['message' => 'Perfil no encontrado']);
        }

        return Response::view('employees/edit-profile', [
            'employee' => $employee,
            'auth' => $auth,
            'errors' => []
        ]);
    }

    /**
     * Actualizar perfil propio
     */
    public function updateProfile(Request $request): Response
    {
        $auth = $this->getAuthInfo($request);
        if (!$auth) {
            return Response::redirect('/login');
        }

        // Obtener empleado del usuario autenticado
        $stmt = $this->db->prepare(
            'SELECT e.*, u.email, u.role FROM employees e JOIN users u ON e.user_id = u.id WHERE e.user_id = :user_id'
        );
        $stmt->execute(['user_id' => $auth['user_id']]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return Response::view('errors/404', ['message' => 'Perfil no encontrado']);
        }

        $data = $request->getParsedBody();
        $data = $this->validator->sanitizeData($data);

        // Validar solo campos permitidos para auto-edición
        $errors = [];
        if (isset($data['first_name'])) {
            $error = $this->validator->validateName($data['first_name'], 'first_name');
            if ($error) $errors['first_name'] = $error;
        }
        if (isset($data['last_name'])) {
            $error = $this->validator->validateName($data['last_name'], 'last_name');
            if ($error) $errors['last_name'] = $error;
        }
        if (isset($data['phone']) && !empty($data['phone'])) {
            $error = $this->validator->validatePhone($data['phone']);
            if ($error) $errors['phone'] = $error;
        }
        if (isset($data['address'])) {
            if (strlen($data['address']) > 500) {
                $errors['address'] = 'La dirección no puede exceder 500 caracteres';
            }
        }

        if (!empty($errors)) {
            return Response::view('employees/edit-profile', [
                'employee' => $employee,
                'auth' => $auth,
                'errors' => $errors,
                'old' => $data
            ]);
        }

        try {
            // Campos permitidos para auto-edición
            $allowedFields = ['first_name', 'last_name', 'phone', 'address', 'city', 'postal_code'];
            $updates = [];
            $params = ['id' => $employee['id']];

            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = :$field";
                    $params[$field] = $data[$field];
                }
            }

            if (!empty($updates)) {
                $sql = 'UPDATE employees SET ' . implode(', ', $updates) . ', updated_at = NOW() WHERE id = :id';
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);

                // Auditoría
                $this->auditLogger->logUpdate(
                    entityType: 'employee',
                    entityId: $employee['id'],
                    oldValues: array_intersect_key($employee, array_flip($allowedFields)),
                    newValues: array_intersect_key($data, array_flip($allowedFields)),
                    userId: $auth['user_id'],
                    userEmail: $auth['email'],
                    userRole: $auth['role']
                );
            }

            return Response::redirect('/employees/profile');
        } catch (Exception $e) {
            error_log($e->getMessage());
            return Response::view('employees/edit-profile', [
                'employee' => $employee,
                'auth' => $auth,
                'errors' => ['system' => 'Error actualizando perfil'],
                'old' => $data
            ]);
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
