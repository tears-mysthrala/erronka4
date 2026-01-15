<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers;

use ZabalaGailetak\HrPortal\Auth\AccessControl;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Services\AuditLogger;
use Exception;

/**
 * Controlador de Auditoría
 * 
 * Gestiona consultas al historial de auditoría
 */
class AuditController
{
    private AuditLogger $auditLogger;
    private AccessControl $accessControl;
    
    public function __construct(AuditLogger $auditLogger, AccessControl $accessControl)
    {
        $this->auditLogger = $auditLogger;
        $this->accessControl = $accessControl;
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
     * GET /api/employees/{id}/history
     * Obtener historial de cambios de un empleado
     */
    public function getEmployeeHistory(Request $request, string $employeeId): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Solo admin y hr_manager pueden ver historial
            if (!$this->accessControl->hasPermission($auth['role'], 'employees.view')) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            // Obtener parámetros de query
            $limit = (int)($request->getQuery('limit') ?? 50);
            $limit = min($limit, 200); // Máximo 200 registros
            
            $history = $this->auditLogger->getEntityHistory('employee', $employeeId, $limit);
            
            return Response::json([
                'employee_id' => $employeeId,
                'history' => $history,
                'count' => count($history)
            ], 200);
            
        } catch (Exception $e) {
            error_log('Error en getEmployeeHistory: ' . $e->getMessage());
            return Response::json(['error' => 'Error obteniendo historial'], 500);
        }
    }
    
    /**
     * GET /api/audit/user/{userId}
     * Obtener actividad de un usuario
     */
    public function getUserActivity(Request $request, string $userId): Response
    {
        try {
            $auth = $this->getAuthInfo($request);
            
            if (!$auth) {
                return Response::json(['error' => 'No autenticado'], 401);
            }
            
            // Solo admin puede ver actividad de otros usuarios
            if ($auth['role'] !== 'admin' && $auth['user_id'] !== $userId) {
                return Response::json(['error' => 'Sin permisos'], 403);
            }
            
            // Obtener parámetros de query
            $limit = (int)($request->getQuery('limit') ?? 50);
            $limit = min($limit, 200);
            $entityType = $request->getQuery('entity_type');
            
            $activity = $this->auditLogger->getUserActivity($userId, $entityType, $limit);
            
            return Response::json([
                'user_id' => $userId,
                'activity' => $activity,
                'count' => count($activity)
            ], 200);
            
        } catch (Exception $e) {
            error_log('Error en getUserActivity: ' . $e->getMessage());
            return Response::json(['error' => 'Error obteniendo actividad'], 500);
        }
    }
}
