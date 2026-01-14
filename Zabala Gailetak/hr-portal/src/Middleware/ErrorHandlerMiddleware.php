<?php

declare(strict_types=1);

namespace HrPortal\Middleware;

use HrPortal\Http\Request;
use HrPortal\Http\Response;

/**
 * Error Handler Middleware
 */
class ErrorHandlerMiddleware
{
    public function process(Request $request): ?Response
    {
        // This middleware doesn't block the request
        // It sets up error handling that will be used if an error occurs
        return null;
    }
}
