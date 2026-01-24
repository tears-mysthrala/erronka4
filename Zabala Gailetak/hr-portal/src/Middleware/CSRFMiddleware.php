<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Middleware;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;

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

        // TODO: Implement CSRF token validation
        return null;
    }
}
