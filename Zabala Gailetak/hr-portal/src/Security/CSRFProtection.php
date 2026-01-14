<?php

declare(strict_types=1);

namespace HrPortal\Security;

/**
 * CSRF Protection Manager
 */
class CSRFProtection
{
    private const TOKEN_LENGTH = 32;
    
    /**
     * Generate CSRF token
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(self::TOKEN_LENGTH));
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Get or create CSRF token for session
     */
    public static function getToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = self::generateToken();
        }
        
        return $_SESSION['csrf_token'];
    }
}
