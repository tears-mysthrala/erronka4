<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Services;

use ZabalaGailetak\HrPortal\Database\Database;
use Exception;

/**
 * Audit Logger Service
 * 
 * Registra todos los cambios realizados en el sistema para auditoría
 */
class AuditLogger
{
    private Database $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    
    /**
     * Registrar creación de entidad
     */
    public function logCreate(
        string $entityType,
        string $entityId,
        array $newValues,
        string $userId,
        string $userEmail,
        string $userRole,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $requestId = null
    ): bool {
        return $this->log(
            entityType: $entityType,
            entityId: $entityId,
            action: 'create',
            oldValues: null,
            newValues: $newValues,
            changedFields: array_keys($newValues),
            userId: $userId,
            userEmail: $userEmail,
            userRole: $userRole,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            requestId: $requestId
        );
    }
    
    /**
     * Registrar actualización de entidad
     */
    public function logUpdate(
        string $entityType,
        string $entityId,
        array $oldValues,
        array $newValues,
        string $userId,
        string $userEmail,
        string $userRole,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $requestId = null
    ): bool {
        // Detectar campos que cambiaron
        $changedFields = [];
        foreach ($newValues as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? null;
            if ($oldValue !== $newValue) {
                $changedFields[] = $field;
            }
        }
        
        // Si no hay cambios, no registrar
        if (empty($changedFields)) {
            return true;
        }
        
        // Filtrar solo los campos que cambiaron
        $filteredOldValues = array_intersect_key($oldValues, array_flip($changedFields));
        $filteredNewValues = array_intersect_key($newValues, array_flip($changedFields));
        
        return $this->log(
            entityType: $entityType,
            entityId: $entityId,
            action: 'update',
            oldValues: $filteredOldValues,
            newValues: $filteredNewValues,
            changedFields: $changedFields,
            userId: $userId,
            userEmail: $userEmail,
            userRole: $userRole,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            requestId: $requestId
        );
    }
    
    /**
     * Registrar eliminación (soft delete)
     */
    public function logDelete(
        string $entityType,
        string $entityId,
        array $oldValues,
        string $userId,
        string $userEmail,
        string $userRole,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $requestId = null
    ): bool {
        return $this->log(
            entityType: $entityType,
            entityId: $entityId,
            action: 'delete',
            oldValues: $oldValues,
            newValues: null,
            changedFields: ['active'],
            userId: $userId,
            userEmail: $userEmail,
            userRole: $userRole,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            requestId: $requestId
        );
    }
    
    /**
     * Registrar restauración
     */
    public function logRestore(
        string $entityType,
        string $entityId,
        array $newValues,
        string $userId,
        string $userEmail,
        string $userRole,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $requestId = null
    ): bool {
        return $this->log(
            entityType: $entityType,
            entityId: $entityId,
            action: 'restore',
            oldValues: ['active' => false],
            newValues: $newValues,
            changedFields: ['active'],
            userId: $userId,
            userEmail: $userEmail,
            userRole: $userRole,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            requestId: $requestId
        );
    }
    
    /**
     * Método interno para registrar en la base de datos
     */
    private function log(
        string $entityType,
        string $entityId,
        string $action,
        ?array $oldValues,
        ?array $newValues,
        array $changedFields,
        string $userId,
        string $userEmail,
        string $userRole,
        ?string $ipAddress,
        ?string $userAgent,
        ?string $requestId
    ): bool {
        try {
            $sql = 'INSERT INTO audit_logs (
                entity_type, entity_id, user_id, user_email, user_role,
                action, old_values, new_values, changed_fields,
                ip_address, user_agent, request_id
            ) VALUES (
                :entity_type, :entity_id, :user_id, :user_email, :user_role,
                :action, :old_values, :new_values, :changed_fields,
                :ip_address, :user_agent, :request_id
            )';
            
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'user_id' => $userId,
                'user_email' => $userEmail,
                'user_role' => $userRole,
                'action' => $action,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'changed_fields' => '{' . implode(',', $changedFields) . '}',
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'request_id' => $requestId
            ]);
            
            return $result !== false;
            
        } catch (Exception $e) {
            error_log('Error logging audit: ' . $e->getMessage());
            // No lanzar excepción para no interrumpir el flujo normal
            return false;
        }
    }
    
    /**
     * Obtener historial de una entidad
     */
    public function getEntityHistory(
        string $entityType,
        string $entityId,
        int $limit = 50
    ): array {
        try {
            $sql = 'SELECT 
                    id, user_email, user_role, action,
                    old_values, new_values, changed_fields,
                    ip_address, created_at
                FROM audit_logs
                WHERE entity_type = :entity_type AND entity_id = :entity_id
                ORDER BY created_at DESC
                LIMIT :limit';
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'limit' => $limit
            ]);
            
            $logs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Decodificar JSON
            foreach ($logs as &$log) {
                $log['old_values'] = $log['old_values'] ? json_decode($log['old_values'], true) : null;
                $log['new_values'] = $log['new_values'] ? json_decode($log['new_values'], true) : null;
                
                // Convertir array PostgreSQL a PHP array
                if ($log['changed_fields']) {
                    $log['changed_fields'] = $this->parsePostgresArray($log['changed_fields']);
                }
            }
            
            return $logs;
            
        } catch (Exception $e) {
            error_log('Error getting entity history: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener historial de un usuario
     */
    public function getUserActivity(
        string $userId,
        ?string $entityType = null,
        int $limit = 50
    ): array {
        try {
            $sql = 'SELECT 
                    id, entity_type, entity_id, action,
                    new_values, changed_fields, created_at
                FROM audit_logs
                WHERE user_id = :user_id';
            
            $params = ['user_id' => $userId, 'limit' => $limit];
            
            if ($entityType) {
                $sql .= ' AND entity_type = :entity_type';
                $params['entity_type'] = $entityType;
            }
            
            $sql .= ' ORDER BY created_at DESC LIMIT :limit';
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log('Error getting user activity: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Parsear array de PostgreSQL
     */
    private function parsePostgresArray(string $pgArray): array
    {
        // Quitar llaves externas
        $pgArray = trim($pgArray, '{}');
        
        if (empty($pgArray)) {
            return [];
        }
        
        // Separar por comas
        return explode(',', $pgArray);
    }
    
    /**
     * Generar ID de request único
     */
    public static function generateRequestId(): string
    {
        return bin2hex(random_bytes(16));
    }
    
    /**
     * Obtener IP del cliente
     */
    public static function getClientIp(): ?string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',  // Cloudflare
            'HTTP_X_FORWARDED_FOR',   // Proxy
            'HTTP_X_REAL_IP',         // Nginx
            'REMOTE_ADDR'             // Direct
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Si es lista de IPs, tomar la primera
                if (str_contains($ip, ',')) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                return $ip;
            }
        }
        
        return null;
    }
    
    /**
     * Obtener User Agent
     */
    public static function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }
}
