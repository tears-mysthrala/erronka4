<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Security;

/**
 * Security Headers Manager
 */
class SecurityHeaders
{
    private static ?string $currentNonce = null;

    /**
     * Generate cryptographically secure nonce for CSP
     */
    public static function generateNonce(): string
    {
        if (self::$currentNonce === null) {
            self::$currentNonce = base64_encode(random_bytes(16));
        }
        return self::$currentNonce;
    }

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
     * Get Content Security Policy with nonce
     */
    private static function getCSP(): string
    {
        $nonce = self::generateNonce();

        $directives = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}'",  // Removed 'unsafe-inline'
            "style-src 'self' 'nonce-{$nonce}'",   // Removed 'unsafe-inline'
            "img-src 'self' data: https:",
            "font-src 'self'",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests",
        ];

        return implode('; ', $directives);
    }
}
