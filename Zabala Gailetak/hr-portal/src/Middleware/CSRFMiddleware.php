<?php

declare(strict_types=1);

namespace HrPortal\Middleware;

use HrPortal\Http\Request;
use HrPortal\Http\Response;

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
