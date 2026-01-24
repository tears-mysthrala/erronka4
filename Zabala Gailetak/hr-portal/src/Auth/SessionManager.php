<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Auth;

use Redis;
use Exception;

/**
 * Session Manager con Redis
 *
 * Gestiona sesiones de usuario utilizando Redis como backend
 * para mejorar performance y escalabilidad
 */
class SessionManager
{
    private Redis $redis;
    private string $prefix = 'session:';
    private int $defaultTtl = 3600; // 1 hora

    public function __construct(Redis $redis, array $config = [])
    {
        if (!class_exists('Redis')) {
            throw new Exception('Redis extension not installed. Install with: pecl install redis');
        }

        $this->redis = $redis;

        if (isset($config['session_prefix'])) {
            $this->prefix = $config['session_prefix'];
        }

        if (isset($config['session_ttl'])) {
            $this->defaultTtl = (int) $config['session_ttl'];
        }
    }

    /**
     * Crea una nueva sesión para un usuario
     */
    public function createSession(string $userId, array $data = [], ?int $ttl = null): string
    {
        $sessionId = $this->generateSessionId();
        $ttl = $ttl ?? $this->defaultTtl;

        $sessionData = [
            'user_id' => $userId,
            'created_at' => time(),
            'last_activity' => time(),
            'data' => $data
        ];

        $key = $this->getKey($sessionId);
        $this->redis->setex($key, $ttl, json_encode($sessionData));

        // Añadir a índice de sesiones del usuario
        $userSessionsKey = $this->getUserSessionsKey($userId);
        $this->redis->sAdd($userSessionsKey, $sessionId);
        $this->redis->expire($userSessionsKey, $ttl);

        return $sessionId;
    }

    /**
     * Obtiene los datos de una sesión
     */
    public function getSession(string $sessionId): ?array
    {
        $key = $this->getKey($sessionId);
        $data = $this->redis->get($key);

        if ($data === false) {
            return null;
        }

        return json_decode($data, true);
    }

    /**
     * Actualiza los datos de una sesión
     */
    public function updateSession(string $sessionId, array $data): bool
    {
        $session = $this->getSession($sessionId);

        if ($session === null) {
            return false;
        }

        $session['last_activity'] = time();
        $session['data'] = array_merge($session['data'], $data);

        $key = $this->getKey($sessionId);
        $ttl = $this->redis->ttl($key);

        if ($ttl < 0) {
            $ttl = $this->defaultTtl;
        }

        return $this->redis->setex($key, $ttl, json_encode($session));
    }

    /**
     * Renueva el TTL de una sesión (keep alive)
     */
    public function refreshSession(string $sessionId, ?int $ttl = null): bool
    {
        $session = $this->getSession($sessionId);

        if ($session === null) {
            return false;
        }

        $session['last_activity'] = time();
        $ttl = $ttl ?? $this->defaultTtl;

        $key = $this->getKey($sessionId);
        return $this->redis->setex($key, $ttl, json_encode($session));
    }

    /**
     * Elimina una sesión
     */
    public function destroySession(string $sessionId): bool
    {
        $session = $this->getSession($sessionId);

        if ($session === null) {
            return false;
        }

        $userId = $session['user_id'];

        // Eliminar de índice de usuario
        $userSessionsKey = $this->getUserSessionsKey($userId);
        $this->redis->sRem($userSessionsKey, $sessionId);

        // Eliminar sesión
        $key = $this->getKey($sessionId);
        return $this->redis->del($key) > 0;
    }

    /**
     * Elimina todas las sesiones de un usuario
     */
    public function destroyAllUserSessions(string $userId): int
    {
        $userSessionsKey = $this->getUserSessionsKey($userId);
        $sessionIds = $this->redis->sMembers($userSessionsKey);

        $count = 0;
        foreach ($sessionIds as $sessionId) {
            $key = $this->getKey($sessionId);
            if ($this->redis->del($key) > 0) {
                $count++;
            }
        }

        // Limpiar índice
        $this->redis->del($userSessionsKey);

        return $count;
    }

    /**
     * Obtiene todas las sesiones activas de un usuario
     */
    public function getUserSessions(string $userId): array
    {
        $userSessionsKey = $this->getUserSessionsKey($userId);
        $sessionIds = $this->redis->sMembers($userSessionsKey);

        $sessions = [];
        foreach ($sessionIds as $sessionId) {
            $session = $this->getSession($sessionId);
            if ($session !== null) {
                $sessions[$sessionId] = $session;
            }
        }

        return $sessions;
    }

    /**
     * Verifica si una sesión existe y está activa
     */
    public function sessionExists(string $sessionId): bool
    {
        $key = $this->getKey($sessionId);
        return $this->redis->exists($key) > 0;
    }

    /**
     * Verifica si una sesión ha expirado por inactividad
     */
    public function isSessionExpired(string $sessionId, int $maxInactivity = 1800): bool
    {
        $session = $this->getSession($sessionId);

        if ($session === null) {
            return true;
        }

        $lastActivity = $session['last_activity'] ?? 0;
        $inactivityTime = time() - $lastActivity;

        return $inactivityTime > $maxInactivity;
    }

    /**
     * Guarda datos temporales en la sesión (flash data)
     */
    public function setFlashData(string $sessionId, string $key, $value): bool
    {
        $flashKey = $this->getFlashKey($sessionId);

        $this->redis->hSet($flashKey, $key, json_encode($value));
        $this->redis->expire($flashKey, 300); // 5 minutos

        return true;
    }

    /**
     * Obtiene y elimina datos flash
     */
    public function getFlashData(string $sessionId, string $key)
    {
        $flashKey = $this->getFlashKey($sessionId);
        $value = $this->redis->hGet($flashKey, $key);

        if ($value === false) {
            return null;
        }

        $this->redis->hDel($flashKey, $key);

        return json_decode($value, true);
    }

    /**
     * Genera un ID de sesión único y seguro
     */
    private function generateSessionId(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Obtiene la key de Redis para una sesión
     */
    private function getKey(string $sessionId): string
    {
        return $this->prefix . $sessionId;
    }

    /**
     * Obtiene la key de índice de sesiones de usuario
     */
    private function getUserSessionsKey(string $userId): string
    {
        return "user_sessions:{$userId}";
    }

    /**
     * Obtiene la key para flash data
     */
    private function getFlashKey(string $sessionId): string
    {
        return "flash:{$sessionId}";
    }

    /**
     * Limpia sesiones expiradas (mantenimiento)
     */
    public function cleanupExpiredSessions(): int
    {
        // Redis maneja automáticamente la expiración con TTL
        // Este método es para limpieza manual si es necesario
        $pattern = $this->prefix . '*';
        $cursor = null;
        $count = 0;

        do {
            $keys = $this->redis->scan($cursor, $pattern, 100);

            if ($keys === false) {
                break;
            }

            foreach ($keys as $key) {
                if ($this->redis->ttl($key) < 0) {
                    $this->redis->del($key);
                    $count++;
                }
            }
        } while ($cursor > 0);

        return $count;
    }
}
