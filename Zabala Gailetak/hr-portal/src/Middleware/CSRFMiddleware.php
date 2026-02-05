<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Middleware;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Security\CSRFProtection;

/**
 * CSRF Protection Middleware
 */
class CSRFMiddleware
{
    public function process(Request $request): ?Response
    {
        // Skip CSRF check for GET requests and API endpoints
        if ($request->isGet() || str_starts_with($request->getUri(), '/api/')) {
            return null;
        }

        // Get CSRF token from request
        $token = $request->getHeader('X-CSRF-Token')
            ?? $request->getPost('csrf_token')
            ?? '';

        // Validate token
        if (!CSRFProtection::validateToken($token)) {
            // Log CSRF violation for SIEM
            error_log(sprintf(
                "[SECURITY] CSRF validation failed - IP: %s, URI: %s, User-Agent: %s",
                $request->getClientIp(),
                $request->getUri(),
                $request->getHeader('User-Agent') ?? 'unknown'
            ));

            return Response::json(
                ['error' => 'CSRF token validation failed', 'code' => 'CSRF_INVALID'],
                403
            );
        }

        return null; // Continue to next middleware
    }
}
