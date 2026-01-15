<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Middleware;

use ZabalaGailetak\HrPortal\Auth\AccessControl;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use Exception;

/**
 * Authorization Middleware
 * 
 * Verifica que el usuario tenga los permisos necesarios
 * para acceder a un recurso
 */
class AuthorizationMiddleware
{
    private AccessControl $accessControl;
    private array $routePermissions = [];
    
    public function __construct(AccessControl $accessControl)
    {
        $this->accessControl = $accessControl;
        $this->initializeRoutePermissions();
    }
    
    private function initializeRoutePermissions(): void
    {
        // Definir permisos requeridos por ruta/pattern
        $this->routePermissions = [
            // Employees
            'GET:/api/employees' => AccessControl::PERM_EMPLOYEES_VIEW_ALL,
            'GET:/api/employees/me' => AccessControl::PERM_EMPLOYEES_VIEW,
            'POST:/api/employees' => AccessControl::PERM_EMPLOYEES_CREATE,
            'PUT:/api/employees/*' => AccessControl::PERM_EMPLOYEES_EDIT,
            'DELETE:/api/employees/*' => AccessControl::PERM_EMPLOYEES_DELETE,
            
            // Vacations
            'GET:/api/vacations' => AccessControl::PERM_VACATIONS_VIEW,
            'POST:/api/vacations' => AccessControl::PERM_VACATIONS_REQUEST,
            'PUT:/api/vacations/*/approve' => AccessControl::PERM_VACATIONS_APPROVE,
            'PUT:/api/vacations/*/reject' => AccessControl::PERM_VACATIONS_REJECT,
            
            // Documents
            'GET:/api/documents' => AccessControl::PERM_DOCUMENTS_VIEW,
            'POST:/api/documents' => AccessControl::PERM_DOCUMENTS_UPLOAD,
            'DELETE:/api/documents/*' => AccessControl::PERM_DOCUMENTS_DELETE,
            
            // Payroll
            'GET:/api/payroll' => AccessControl::PERM_PAYROLL_VIEW,
            'GET:/api/payroll/all' => AccessControl::PERM_PAYROLL_VIEW_ALL,
            'POST:/api/payroll' => AccessControl::PERM_PAYROLL_CREATE,
            
            // Chat
            'GET:/api/chat' => AccessControl::PERM_CHAT_ACCESS,
            'POST:/api/chat/hr' => AccessControl::PERM_CHAT_HR,
            'POST:/api/chat/department' => AccessControl::PERM_CHAT_DEPARTMENT,
            
            // Complaints
            'GET:/api/complaints' => AccessControl::PERM_COMPLAINTS_VIEW,
            'GET:/api/complaints/all' => AccessControl::PERM_COMPLAINTS_VIEW_ALL,
            'POST:/api/complaints' => AccessControl::PERM_COMPLAINTS_CREATE,
            'PUT:/api/complaints/*/respond' => AccessControl::PERM_COMPLAINTS_RESPOND,
            
            // Reports
            'GET:/api/reports' => AccessControl::PERM_REPORTS_VIEW,
            'POST:/api/reports/generate' => AccessControl::PERM_REPORTS_GENERATE,
            
            // Audit
            'GET:/api/audit' => AccessControl::PERM_AUDIT_VIEW,
            
            // Settings
            'GET:/api/settings' => AccessControl::PERM_SETTINGS_MANAGE,
            'PUT:/api/settings' => AccessControl::PERM_SETTINGS_MANAGE,
        ];
    }
    
    public function __invoke(Request $request, callable $next): Response
    {
        // Obtener rol del usuario (ya cargado por AuthenticationMiddleware)
        $userRole = $request->getAttribute('user_role');
        
        if (!$userRole) {
            return Response::json([
                'error' => 'Usuario no autenticado'
            ], 401);
        }
        
        // Obtener método y path
        $method = $request->getMethod();
        $path = $request->getUri();
        
        // Buscar permiso requerido para esta ruta
        $requiredPermission = $this->findRequiredPermission($method, $path);
        
        // Si no hay permiso definido, permitir acceso (rutas sin restricciones)
        if ($requiredPermission === null) {
            return $next($request);
        }
        
        // Verificar permiso
        if (!$this->accessControl->hasPermission($userRole, $requiredPermission)) {
            return Response::json([
                'error' => 'No tienes permiso para acceder a este recurso',
                'required_permission' => $requiredPermission
            ], 403);
        }
        
        // Usuario autorizado, continuar
        return $next($request);
    }
    
    private function findRequiredPermission(string $method, string $path): ?string
    {
        $routeKey = "$method:$path";
        
        // Búsqueda exacta
        if (isset($this->routePermissions[$routeKey])) {
            return $this->routePermissions[$routeKey];
        }
        
        // Búsqueda con wildcards
        foreach ($this->routePermissions as $pattern => $permission) {
            if ($this->matchesPattern($pattern, $routeKey)) {
                return $permission;
            }
        }
        
        return null;
    }
    
    private function matchesPattern(string $pattern, string $routeKey): bool
    {
        // Convertir patrón con wildcards a regex
        $regex = str_replace('*', '[^/]+', $pattern);
        $regex = '#^' . $regex . '$#';
        
        return preg_match($regex, $routeKey) === 1;
    }
    
    public function addRoutePermission(string $method, string $path, string $permission): void
    {
        $routeKey = "$method:$path";
        $this->routePermissions[$routeKey] = $permission;
    }
}
