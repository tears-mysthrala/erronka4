<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Auth;

use Exception;

/**
 * Access Control - Control de acceso basado en roles (RBAC)
 *
 * Define y gestiona permisos para diferentes roles de usuario
 */
class AccessControl
{
    // Definición de roles
    public const ROLE_ADMIN = 'admin';
    public const ROLE_HR_MANAGER = 'hr_manager';
    public const ROLE_DEPARTMENT_HEAD = 'department_head';
    public const ROLE_EMPLOYEE = 'employee';

    // Definición de permisos
    public const PERM_USERS_VIEW = 'users.view';
    public const PERM_USERS_CREATE = 'users.create';
    public const PERM_USERS_EDIT = 'users.edit';
    public const PERM_USERS_DELETE = 'users.delete';

    public const PERM_EMPLOYEES_VIEW = 'employees.view';
    public const PERM_EMPLOYEES_VIEW_ALL = 'employees.view_all';
    public const PERM_EMPLOYEES_VIEW_DEPARTMENT = 'employees.view_department';
    public const PERM_EMPLOYEES_CREATE = 'employees.create';
    public const PERM_EMPLOYEES_EDIT = 'employees.edit';
    public const PERM_EMPLOYEES_DELETE = 'employees.delete';

    public const PERM_VACATIONS_VIEW = 'vacations.view';
    public const PERM_VACATIONS_VIEW_ALL = 'vacations.view_all';
    public const PERM_VACATIONS_VIEW_DEPARTMENT = 'vacations.view_department';
    public const PERM_VACATIONS_REQUEST = 'vacations.request';
    public const PERM_VACATIONS_APPROVE = 'vacations.approve';
    public const PERM_VACATIONS_REJECT = 'vacations.reject';

    public const PERM_DOCUMENTS_VIEW = 'documents.view';
    public const PERM_DOCUMENTS_VIEW_ALL = 'documents.view_all';
    public const PERM_DOCUMENTS_UPLOAD = 'documents.upload';
    public const PERM_DOCUMENTS_DELETE = 'documents.delete';

    public const PERM_PAYROLL_VIEW = 'payroll.view';
    public const PERM_PAYROLL_VIEW_ALL = 'payroll.view_all';
    public const PERM_PAYROLL_CREATE = 'payroll.create';
    public const PERM_PAYROLL_EDIT = 'payroll.edit';

    public const PERM_CHAT_ACCESS = 'chat.access';
    public const PERM_CHAT_HR = 'chat.hr';
    public const PERM_CHAT_DEPARTMENT = 'chat.department';

    public const PERM_COMPLAINTS_VIEW = 'complaints.view';
    public const PERM_COMPLAINTS_VIEW_ALL = 'complaints.view_all';
    public const PERM_COMPLAINTS_CREATE = 'complaints.create';
    public const PERM_COMPLAINTS_RESPOND = 'complaints.respond';

    public const PERM_REPORTS_VIEW = 'reports.view';
    public const PERM_REPORTS_GENERATE = 'reports.generate';

    public const PERM_AUDIT_VIEW = 'audit.view';
    public const PERM_SETTINGS_MANAGE = 'settings.manage';

    /**
     * Matriz de permisos por rol
     */
    private array $rolePermissions = [];

    public function __construct()
    {
        $this->initializeRolePermissions();
    }

    /**
     * Inicializa la matriz de permisos
     */
    private function initializeRolePermissions(): void
    {
        // Admin - Acceso completo
        $this->rolePermissions[self::ROLE_ADMIN] = [
            self::PERM_USERS_VIEW,
            self::PERM_USERS_CREATE,
            self::PERM_USERS_EDIT,
            self::PERM_USERS_DELETE,
            self::PERM_EMPLOYEES_VIEW,
            self::PERM_EMPLOYEES_VIEW_ALL,
            self::PERM_EMPLOYEES_CREATE,
            self::PERM_EMPLOYEES_EDIT,
            self::PERM_EMPLOYEES_DELETE,
            self::PERM_VACATIONS_VIEW,
            self::PERM_VACATIONS_VIEW_ALL,
            self::PERM_VACATIONS_REQUEST,
            self::PERM_VACATIONS_APPROVE,
            self::PERM_VACATIONS_REJECT,
            self::PERM_DOCUMENTS_VIEW,
            self::PERM_DOCUMENTS_VIEW_ALL,
            self::PERM_DOCUMENTS_UPLOAD,
            self::PERM_DOCUMENTS_DELETE,
            self::PERM_PAYROLL_VIEW,
            self::PERM_PAYROLL_VIEW_ALL,
            self::PERM_PAYROLL_CREATE,
            self::PERM_PAYROLL_EDIT,
            self::PERM_CHAT_ACCESS,
            self::PERM_CHAT_HR,
            self::PERM_CHAT_DEPARTMENT,
            self::PERM_COMPLAINTS_VIEW,
            self::PERM_COMPLAINTS_VIEW_ALL,
            self::PERM_COMPLAINTS_CREATE,
            self::PERM_COMPLAINTS_RESPOND,
            self::PERM_REPORTS_VIEW,
            self::PERM_REPORTS_GENERATE,
            self::PERM_AUDIT_VIEW,
            self::PERM_SETTINGS_MANAGE,
        ];

        // HR Manager - Gestión de RRHH
        $this->rolePermissions[self::ROLE_HR_MANAGER] = [
            self::PERM_EMPLOYEES_VIEW,
            self::PERM_EMPLOYEES_VIEW_ALL,
            self::PERM_EMPLOYEES_CREATE,
            self::PERM_EMPLOYEES_EDIT,
            self::PERM_VACATIONS_VIEW,
            self::PERM_VACATIONS_VIEW_ALL,
            self::PERM_VACATIONS_REQUEST,
            self::PERM_VACATIONS_APPROVE,
            self::PERM_VACATIONS_REJECT,
            self::PERM_DOCUMENTS_VIEW,
            self::PERM_DOCUMENTS_VIEW_ALL,
            self::PERM_DOCUMENTS_UPLOAD,
            self::PERM_PAYROLL_VIEW,
            self::PERM_PAYROLL_VIEW_ALL,
            self::PERM_PAYROLL_CREATE,
            self::PERM_PAYROLL_EDIT,
            self::PERM_CHAT_ACCESS,
            self::PERM_CHAT_HR,
            self::PERM_CHAT_DEPARTMENT,
            self::PERM_COMPLAINTS_VIEW,
            self::PERM_COMPLAINTS_VIEW_ALL,
            self::PERM_COMPLAINTS_CREATE,
            self::PERM_COMPLAINTS_RESPOND,
            self::PERM_REPORTS_VIEW,
            self::PERM_REPORTS_GENERATE,
        ];

        // Department Head - Jefe de departamento
        $this->rolePermissions[self::ROLE_DEPARTMENT_HEAD] = [
            self::PERM_EMPLOYEES_VIEW,
            self::PERM_EMPLOYEES_VIEW_DEPARTMENT,
            self::PERM_VACATIONS_VIEW,
            self::PERM_VACATIONS_VIEW_DEPARTMENT,
            self::PERM_VACATIONS_REQUEST,
            self::PERM_VACATIONS_APPROVE,
            self::PERM_VACATIONS_REJECT,
            self::PERM_DOCUMENTS_VIEW,
            self::PERM_DOCUMENTS_UPLOAD,
            self::PERM_PAYROLL_VIEW,
            self::PERM_CHAT_ACCESS,
            self::PERM_CHAT_DEPARTMENT,
            self::PERM_COMPLAINTS_VIEW,
            self::PERM_COMPLAINTS_CREATE,
            self::PERM_REPORTS_VIEW,
        ];

        // Employee - Empleado regular
        $this->rolePermissions[self::ROLE_EMPLOYEE] = [
            self::PERM_EMPLOYEES_VIEW, // Solo su propio perfil
            self::PERM_VACATIONS_VIEW,
            self::PERM_VACATIONS_REQUEST,
            self::PERM_DOCUMENTS_VIEW,
            self::PERM_DOCUMENTS_UPLOAD,
            self::PERM_PAYROLL_VIEW,
            self::PERM_CHAT_ACCESS,
            self::PERM_CHAT_DEPARTMENT,
            self::PERM_COMPLAINTS_VIEW,
            self::PERM_COMPLAINTS_CREATE,
        ];
    }

    /**
     * Verifica si un rol tiene un permiso específico
     */
    public function hasPermission(string $role, string $permission): bool
    {
        if (!isset($this->rolePermissions[$role])) {
            return false;
        }

        return in_array($permission, $this->rolePermissions[$role], true);
    }

    /**
     * Verifica si un usuario (por su rol) puede realizar una acción
     */
    public function can(string $role, string $permission): bool
    {
        return $this->hasPermission($role, $permission);
    }

    /**
     * Verifica si un usuario NO puede realizar una acción
     */
    public function cannot(string $role, string $permission): bool
    {
        return !$this->can($role, $permission);
    }

    /**
     * Obtiene todos los permisos de un rol
     */
    public function getRolePermissions(string $role): array
    {
        return $this->rolePermissions[$role] ?? [];
    }

    /**
     * Verifica si un rol es válido
     */
    public function isValidRole(string $role): bool
    {
        return isset($this->rolePermissions[$role]);
    }

    /**
     * Obtiene todos los roles disponibles
     */
    public function getAllRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_HR_MANAGER,
            self::ROLE_DEPARTMENT_HEAD,
            self::ROLE_EMPLOYEE,
        ];
    }

    /**
     * Verifica si un rol es administrador
     */
    public function isAdmin(string $role): bool
    {
        return $role === self::ROLE_ADMIN;
    }

    /**
     * Verifica si un rol es HR Manager
     */
    public function isHrManager(string $role): bool
    {
        return $role === self::ROLE_HR_MANAGER;
    }

    /**
     * Verifica si un rol es jefe de departamento
     */
    public function isDepartmentHead(string $role): bool
    {
        return $role === self::ROLE_DEPARTMENT_HEAD;
    }

    /**
     * Verifica si un rol tiene privilegios de gestión
     */
    public function hasManagementPrivileges(string $role): bool
    {
        return in_array($role, [
            self::ROLE_ADMIN,
            self::ROLE_HR_MANAGER,
            self::ROLE_DEPARTMENT_HEAD,
        ], true);
    }

    /**
     * Verifica múltiples permisos (requiere todos)
     */
    public function hasAllPermissions(string $role, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($role, $permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica múltiples permisos (requiere al menos uno)
     */
    public function hasAnyPermission(string $role, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($role, $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Valida acceso y lanza excepción si no tiene permiso
     */
    public function authorize(
        string $role,
        string $permission,
        string $message = 'No tienes permiso para realizar esta acción'
    ): void {
        if (!$this->hasPermission($role, $permission)) {
            throw new Exception($message, 403);
        }
    }

    /**
     * Obtiene el nombre legible de un rol
     */
    public function getRoleName(string $role): string
    {
        $names = [
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_HR_MANAGER => 'Responsable de RRHH',
            self::ROLE_DEPARTMENT_HEAD => 'Jefe de Departamento',
            self::ROLE_EMPLOYEE => 'Empleado',
        ];

        return $names[$role] ?? 'Desconocido';
    }
}
