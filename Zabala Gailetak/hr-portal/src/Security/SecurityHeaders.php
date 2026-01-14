<?php

declare(strict_types=1);

namespace HrPortal\Security;

/**
 * Security Headers Manager
 */
class SecurityHeaders
{
    /**
     * Get all security headers
     */
    public static function getHeaders(): array
    {
        return [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
            'Content-Security-Policy' => self::getCSP(),
        ];
    }
    
    /**
     * Get Content Security Policy
     */
    private static function getCSP(): string
    {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data:",
            "font-src 'self'",
            "connect-src 'self'",
            "frame-ancestors 'self'",
        ];
        
        return implode('; ', $directives);
    }
}
