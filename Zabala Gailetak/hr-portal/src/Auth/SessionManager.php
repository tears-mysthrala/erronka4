<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Auth;

/**
 * Session Manager (Native)
 *
 * Gestiona sesiones de usuario utilizando PHP nativo ($_SESSION).
 * Reemplaza la implementación anterior de Redis para entornos Zero Trust/Hosting Compartido.
 */
class SessionManager
{
    private string $prefix = 'hrportal:';

    public function __construct(array $config = [])
    {
        if (isset($config['session_prefix'])) {
            $this->prefix = $config['session_prefix'];
        }

        if (session_status() === PHP_SESSION_NONE) {
            // Secure session settings
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }

            session_start();
        }
    }

    public function createSession(string $userId, array $data = [], ?int $ttl = null): string
    {
        session_regenerate_id(true);
        $_SESSION[$this->prefix . 'user_id'] = $userId;
        $_SESSION[$this->prefix . 'created_at'] = time();
        $_SESSION[$this->prefix . 'last_activity'] = time();
        $_SESSION[$this->prefix . 'data'] = $data;

        // Generate new CSRF token for the session
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return session_id();
    }

    public function getSession(string $sessionId): ?array
    {
        if ($sessionId !== session_id()) {
            return null; // Can't access other sessions in native mode easily
        }

        if (!isset($_SESSION[$this->prefix . 'user_id'])) {
            return null;
        }

        return [
            'user_id' => $_SESSION[$this->prefix . 'user_id'],
            'created_at' => $_SESSION[$this->prefix . 'created_at'],
            'last_activity' => $_SESSION[$this->prefix . 'last_activity'],
            'data' => $_SESSION[$this->prefix . 'data'] ?? []
        ];
    }

    public function updateSession(string $sessionId, array $data): bool
    {
        if ($sessionId !== session_id()) {
            return false;
        }

        $_SESSION[$this->prefix . 'last_activity'] = time();
        $_SESSION[$this->prefix . 'data'] = array_merge($_SESSION[$this->prefix . 'data'] ?? [], $data);
        return true;
    }

    public function destroySession(string $sessionId): bool
    {
        if ($sessionId === session_id()) {
            session_destroy();
            return true;
        }
        return false;
    }

    public function sessionExists(string $sessionId): bool
    {
        return $sessionId === session_id() && isset($_SESSION[$this->prefix . 'user_id']);
    }

    public function refreshSession(string $sessionId): void
    {
        if ($sessionId === session_id()) {
            $_SESSION[$this->prefix . 'last_activity'] = time();
        }
    }
}
