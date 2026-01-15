<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers;

use ZabalaGailetak\HrPortal\Auth\AccessControl;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Services\AuditLogger;
use ZabalaGailetak\HrPortal\Validation\EmployeeValidator;
use Exception;
use PDO;

/**
 * Controlador de Empleados
 * 
 * Maneja operaciones CRUD de empleados con permisos por rol
 */
class EmployeeController
{
    private Database $db;
    private AccessControl $accessControl;
    private EmployeeValidator $validator;
    private AuditLogger $auditLogger;
    
    public function __construct(
        Database $db, 
        AccessControl $accessControl, 
        ?EmployeeValidator $validator = null,
        ?AuditLogger $auditLogger = null
    ) {
        $this->db = $db;
        $this->accessControl = $accessControl;
        $this->validator = $validator ?? new EmployeeValidator();
        $this->auditLogger = $auditLogger ?? new AuditLogger($db);
    }
    
    /**
     * Extraer userId y role desde atributos de request
     */
    private function getAuthInfo(Request $request): ?array
    {
        $userId = $request->getAttribute('user_id');
        $userRole = $request->getAttribute('user_role');
        
        if (!$userId || !$userRole) {
            return null;
        }
        
        return ['user_id' => $userId, 'role' => $userRole];
    }
    
    /**
     * GET /api/employees
     * Lista de empleados con paginación y filtros
     */
    public function index(Request $request): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Verificar permisos
            if (!$this->accessControl->hasPermission($auth['role'], 'employees.view')) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            // Parámetros de paginación
            $page = (int)($request->getQuery('page') ?? 1);
            $limit = (int)($request->getQuery('limit') ?? 20);
            $offset = ($page - 1) * $limit;
            
            // Parámetros de filtrado
            $search = $request->getQuery('search');
            $departmentId = $request->getQuery('department_id');
            $active = $request->getQuery('active');
            
            // Construir query base
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
            
            // Filtro por búsqueda (nombre, email, employee_number)
            if ($search) {
                $whereConditions[] = "(e.first_name ILIKE :search 
                    OR e.last_name ILIKE :search 
                    OR e.employee_number ILIKE :search 
                    OR u.email ILIKE :search)";
                $params['search'] = "%{$search}%";
            }
            
            // Filtro por departamento
            if ($departmentId) {
                $whereConditions[] = 'e.department_id = :department_id';
                $params['department_id'] = $departmentId;
            }
            
            // Filtro por estado activo
            if ($active !== null) {
                $whereConditions[] = 'e.active = :active';
                $params['active'] = $active === 'true' || $active === '1';
            }
            
            $whereClause = !empty($whereConditions) 
                ? 'WHERE ' . implode(' AND ', $whereConditions)
                : '';
            
            // Contar total
            $countSql = "SELECT COUNT(*) 
                FROM employees e
                INNER JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                $whereClause";
            
            $stmt = $this->db->prepare($countSql);
            $stmt->execute($params);
            $total = (int)$stmt->fetchColumn();
            
            // Obtener datos
            $sql = "SELECT 
                    e.id,
                    e.employee_number,
                    e.first_name,
                    e.last_name,
                    e.nif,
                    e.position,
                    e.hire_date,
                    e.active,
                    e.user_id,
                    u.email,
                    u.role,
                    d.id as department_id,
                    d.name as department_name
                FROM employees e
                INNER JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                $whereClause
                ORDER BY e.last_name, e.first_name
                LIMIT :limit OFFSET :offset";
            
            $params['limit'] = $limit;
            $params['offset'] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return Response::json([
                'data' => $employees,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit,
                    'pages' => (int)ceil($total / $limit)
                ]
            ], 200);
            
        } catch (Exception $e) {
            error_log('Error en index: ' . $e->getMessage());
            return Response::json([
                'error' => 'Error obteniendo empleados'
            ], 500);
        }
    }
    
    /**
     * GET /api/employees/{id}
     * Obtener detalle de un empleado
     */
    public function show(Request $request, string $id): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Verificar permisos
            if (!$this->accessControl->hasPermission($auth['role'], 'employees.view')) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            $sql = "SELECT 
                    e.*,
                    u.email,
                    u.role,
                    u.mfa_enabled,
                    d.id as department_id,
                    d.name as department_name
                FROM employees e
                INNER JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE e.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$employee) {
                return Response::json(['error' => 'Empleado no encontrado'], 404);
            }
            
            // Si es jefe de sección, solo puede ver su departamento
            if ($auth['role'] === 'department_head') {
                $stmt = $this->db->prepare(
                    'SELECT department_id FROM employees WHERE user_id = :user_id'
                );
                $stmt->execute(['user_id' => $auth['user_id']]);
                $userDeptId = $stmt->fetchColumn();
                
                if ($userDeptId != $employee['department_id']) {
                    return Response::json(['error' => 'Sin permisos'], 403);
                }
            }
            
            // Si es empleado, solo puede ver su propio perfil
            if ($auth['role'] === 'employee' && $auth['user_id'] !== $employee['user_id']) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            return Response::json(['employee' => $employee], 200);
            
        } catch (Exception $e) {
            error_log('Error en show: ' . $e->getMessage());
            return Response::json(['error' => 'Error obteniendo empleado'], 500);
        }
    }
    
    /**
     * POST /api/employees
     * Crear nuevo empleado
     */
    public function create(Request $request): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Solo admin y hr_manager pueden crear empleados
            if (!$this->accessControl->hasPermission($auth['role'], 'employees.create')) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            $data = $request->getParsedBody();
            
            // Sanitizar datos de entrada
            $data = $this->validator->sanitizeData($data);
            
            // Validar datos
            $validationErrors = $this->validator->validate($data, false);
            if (!empty($validationErrors)) {
                return Response::json([
                    'error' => 'Errores de validación',
                    'validation_errors' => $validationErrors
                ], 400);
            }
            
            // Iniciar transacción
            $this->db->beginTransaction();
            
            try {
                // 1. Crear usuario
                $userRole = $data['role'] ?? 'employee';
                $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
                
                $stmt = $this->db->prepare(
                    'INSERT INTO users (email, password_hash, role) 
                    VALUES (:email, :password_hash, :role) 
                    RETURNING id'
                );
                $stmt->execute([
                    'email' => $data['email'],
                    'password_hash' => $passwordHash,
                    'role' => $userRole
                ]);
                $userId = $stmt->fetchColumn();
                
                // 2. Crear empleado
                $stmt = $this->db->prepare(
                    'INSERT INTO employees (
                        user_id, first_name, last_name, nif, position,
                        employee_number, phone, address, city, postal_code,
                        country, hire_date, department_id, salary, bank_account
                    ) VALUES (
                        :user_id, :first_name, :last_name, :nif, :position,
                        :employee_number, :phone, :address, :city, :postal_code,
                        :country, :hire_date, :department_id, :salary, :bank_account
                    ) RETURNING id'
                );
                
                $employeeNumber = $data['employee_number'] ?? $this->generateEmployeeNumber();
                
                $stmt->execute([
                    'user_id' => $userId,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'nif' => $data['nif'],
                    'position' => $data['position'],
                    'employee_number' => $employeeNumber,
                    'phone' => $data['phone'] ?? null,
                    'address' => $data['address'] ?? null,
                    'city' => $data['city'] ?? null,
                    'postal_code' => $data['postal_code'] ?? null,
                    'country' => $data['country'] ?? 'España',
                    'hire_date' => $data['hire_date'] ?? date('Y-m-d'),
                    'department_id' => $data['department_id'] ?? null,
                    'salary' => $data['salary'] ?? null,
                    'bank_account' => $data['bank_account'] ?? null
                ]);
                
                $employeeId = $stmt->fetchColumn();
                
                $this->db->commit();
                
                // Registrar en audit log
                $this->auditLogger->logCreate(
                    entityType: 'employee',
                    entityId: (string)$employeeId,
                    newValues: [
                        'employee_number' => $employeeNumber,
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'nif' => $data['nif'],
                        'position' => $data['position'],
                        'email' => $data['email'],
                        'role' => $userRole,
                        'hire_date' => $data['hire_date'] ?? date('Y-m-d'),
                        'department_id' => $data['department_id'] ?? null
                    ],
                    userId: $auth['user_id'],
                    userEmail: $request->getAttribute('user_email') ?? '',
                    userRole: $auth['role'],
                    ipAddress: AuditLogger::getClientIp(),
                    userAgent: AuditLogger::getUserAgent(),
                    requestId: AuditLogger::generateRequestId()
                );
                
                return Response::json([
                    'message' => 'Empleado creado exitosamente',
                    'employee_id' => $employeeId,
                    'user_id' => $userId,
                    'employee_number' => $employeeNumber
                ], 201);
                
            } catch (Exception $e) {
                $this->db->rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log('Error en create: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), 'unique') !== false) {
                return Response::json([
                    'error' => 'El email o NIF ya existe'
                ], 409);
            }
            
            return Response::json(['error' => 'Error creando empleado'], 500);
        }
    }
    
    /**
     * PUT /api/employees/{id}
     * Actualizar empleado
     */
    public function update(Request $request, string $id): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Solo admin y hr_manager pueden editar
            if (!$this->accessControl->hasPermission($auth['role'], 'employees.edit')) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            $data = $request->getParsedBody();
            
            // Sanitizar datos de entrada
            $data = $this->validator->sanitizeData($data);
            
            // Validar datos (solo campos presentes)
            $validationErrors = $this->validator->validate($data, true);
            if (!empty($validationErrors)) {
                return Response::json([
                    'error' => 'Errores de validación',
                    'validation_errors' => $validationErrors
                ], 400);
            }
            
            // Verificar que el empleado existe y obtener valores actuales
            $stmt = $this->db->prepare('
                SELECT e.*, u.email, u.role 
                FROM employees e 
                JOIN users u ON e.user_id = u.id 
                WHERE e.id = :id
            ');
            $stmt->execute(['id' => $id]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$employee) {
                return Response::json(['error' => 'Empleado no encontrado'], 404);
            }
            
            // Construir UPDATE dinámico
            $allowedFields = [
                'first_name', 'last_name', 'nif', 'position', 'phone',
                'address', 'city', 'postal_code', 'country', 'hire_date',
                'department_id', 'salary', 'bank_account', 'active'
            ];
            
            $updates = [];
            $params = ['id' => $id];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updates[] = "{$field} = :{$field}";
                    $params[$field] = $data[$field];
                }
            }
            
            if (empty($updates)) {
                return Response::json(['error' => 'No hay campos para actualizar'], 400);
            }
            
            $sql = 'UPDATE employees SET ' . implode(', ', $updates) . 
                   ', updated_at = NOW() WHERE id = :id';
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            // Si se actualiza el email, actualizar en users
            if (isset($data['email'])) {
                $stmt = $this->db->prepare(
                    'UPDATE users SET email = :email WHERE id = :user_id'
                );
                $stmt->execute([
                    'email' => $data['email'],
                    'user_id' => $employee['user_id']
                ]);
            }
            
            // Registrar cambios en audit log
            $oldValues = [];
            $newValues = [];
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $oldValues[$field] = $employee[$field];
                    $newValues[$field] = $data[$field];
                }
            }
            if (isset($data['email'])) {
                $oldValues['email'] = $employee['email'];
                $newValues['email'] = $data['email'];
            }
            
            $this->auditLogger->logUpdate(
                entityType: 'employee',
                entityId: $id,
                oldValues: $oldValues,
                newValues: $newValues,
                userId: $auth['user_id'],
                userEmail: $request->getAttribute('user_email') ?? '',
                userRole: $auth['role'],
                ipAddress: AuditLogger::getClientIp(),
                userAgent: AuditLogger::getUserAgent(),
                requestId: AuditLogger::generateRequestId()
            );
            
            return Response::json([
                'message' => 'Empleado actualizado exitosamente',
                'employee_id' => $id
            ], 200);
            
        } catch (Exception $e) {
            error_log('Error en update: ' . $e->getMessage());
            return Response::json(['error' => 'Error actualizando empleado'], 500);
        }
    }
    
    /**
     * DELETE /api/employees/{id}
     * Baja lógica de empleado
     */
    public function delete(Request $request, string $id): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Solo admin puede dar de baja
            if (!$this->accessControl->hasPermission($auth['role'], 'employees.delete')) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            // Obtener datos del empleado antes de dar de baja
            $stmt = $this->db->prepare('
                SELECT e.*, u.email 
                FROM employees e 
                JOIN users u ON e.user_id = u.id 
                WHERE e.id = :id
            ');
            $stmt->execute(['id' => $id]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$employee) {
                return Response::json(['error' => 'Empleado no encontrado'], 404);
            }
            
            // Soft delete: marcar como inactivo
            $stmt = $this->db->prepare(
                'UPDATE employees SET active = false, updated_at = NOW() WHERE id = :id'
            );
            $stmt->execute(['id' => $id]);
            
            // Registrar en audit log
            $this->auditLogger->logDelete(
                entityType: 'employee',
                entityId: $id,
                oldValues: [
                    'employee_number' => $employee['employee_number'],
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'position' => $employee['position'],
                    'active' => true
                ],
                userId: $auth['user_id'],
                userEmail: $request->getAttribute('user_email') ?? '',
                userRole: $auth['role'],
                ipAddress: AuditLogger::getClientIp(),
                userAgent: AuditLogger::getUserAgent(),
                requestId: AuditLogger::generateRequestId()
            );
            
            return Response::json([
                'message' => 'Empleado dado de baja exitosamente'
            ], 200);
            
        } catch (Exception $e) {
            error_log('Error en delete: ' . $e->getMessage());
            return Response::json(['error' => 'Error dando de baja empleado'], 500);
        }
    }
    
    /**
     * POST /api/employees/{id}/restore
     * Reactivar empleado
     */
    public function restore(Request $request, string $id): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Solo admin puede reactivar
            if ($auth['role'] !== 'admin') {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            // Obtener datos del empleado antes de reactivar
            $stmt = $this->db->prepare('
                SELECT e.*, u.email 
                FROM employees e 
                JOIN users u ON e.user_id = u.id 
                WHERE e.id = :id
            ');
            $stmt->execute(['id' => $id]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$employee) {
                return Response::json(['error' => 'Empleado no encontrado'], 404);
            }
            
            $stmt = $this->db->prepare(
                'UPDATE employees SET active = true, updated_at = NOW() WHERE id = :id'
            );
            $stmt->execute(['id' => $id]);
            
            // Registrar en audit log
            $this->auditLogger->logRestore(
                entityType: 'employee',
                entityId: $id,
                newValues: [
                    'employee_number' => $employee['employee_number'],
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'position' => $employee['position'],
                    'active' => true
                ],
                userId: $auth['user_id'],
                userEmail: $request->getAttribute('user_email') ?? '',
                userRole: $auth['role'],
                ipAddress: AuditLogger::getClientIp(),
                userAgent: AuditLogger::getUserAgent(),
                requestId: AuditLogger::generateRequestId()
            );
            
            return Response::json([
                'message' => 'Empleado reactivado exitosamente'
            ], 200);
            
        } catch (Exception $e) {
            error_log('Error en restore: ' . $e->getMessage());
            return Response::json(['error' => 'Error reactivando empleado'], 500);
        }
    }
    
    /**
     * Genera un número de empleado único
     */
    private function generateEmployeeNumber(): string
    {
        $prefix = 'EMP';
        $year = date('Y');
        
        // Obtener el último número del año
        $stmt = $this->db->prepare(
            "SELECT employee_number FROM employees 
            WHERE employee_number LIKE :pattern 
            ORDER BY employee_number DESC LIMIT 1"
        );
        $stmt->execute(['pattern' => "{$prefix}{$year}%"]);
        $lastNumber = $stmt->fetchColumn();
        
        if ($lastNumber) {
            $sequence = (int)substr($lastNumber, -4) + 1;
        } else {
            $sequence = 1;
        }
        
        return sprintf('%s%s%04d', $prefix, $year, $sequence);
    }
}
