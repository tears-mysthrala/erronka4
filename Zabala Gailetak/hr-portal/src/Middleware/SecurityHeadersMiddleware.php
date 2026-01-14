<?php

declare(strict_types=1);

namespace HrPortal\Middleware;

use HrPortal\Http\Request;
use HrPortal\Http\Response;

/**
 * Security Headers Middleware
 */
class SecurityHeadersMiddleware
{
    public function process(Request $request): ?Response
    {
        // Headers are applied in App.php when sending response
        // This middleware doesn't block the request
        return null;
    }
}
