<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Routing\Router;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Security\CSRFProtection;
use ZabalaGailetak\HrPortal\Security\SecurityHeaders;
use ZabalaGailetak\HrPortal\Middleware\ErrorHandlerMiddleware;
use ZabalaGailetak\HrPortal\Middleware\SecurityHeadersMiddleware;
use ZabalaGailetak\HrPortal\Middleware\CSRFMiddleware;

/**
 * Main Application Class
 *
 * Handles application initialization and request/response lifecycle
 */
class App
{
    private Router $router;
    private Database $database;
    private array $middleware = [];

    public function __construct()
    {
        // Initialize database connection
        $this->database = new Database();

        // Initialize router
        $this->router = new Router();

        // Register global middleware
        $this->registerMiddleware();

        // Load routes
        $this->loadRoutes();
    }

    /**
     * Register global middleware
     */
    private function registerMiddleware(): void
    {
        $this->middleware = [
            new ErrorHandlerMiddleware(),
            new SecurityHeadersMiddleware(),
            new CSRFMiddleware(),
        ];
    }

    /**
     * Load application routes
     */
    private function loadRoutes(): void
    {
        $GLOBALS['app'] = $this;
        require ROOT_PATH . '/config/routes.php';
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        // Create request from globals
        $request = Request::fromGlobals();

        // Process through middleware stack
        $response = $this->processMiddleware($request);

        // If no middleware handled the request, pass to router
        if ($response === null) {
            $response = $this->router->dispatch($request);
        }

        // Send response
        $this->sendResponse($response);
    }

    /**
     * Process request through middleware stack
     */
    private function processMiddleware(Request $request): ?Response
    {
        foreach ($this->middleware as $middleware) {
            $response = $middleware->process($request);
            if ($response !== null) {
                return $response;
            }
        }

        return null;
    }

    /**
     * Send HTTP response
     */
    private function sendResponse(Response $response): void
    {
        // Set status code
        http_response_code($response->getStatusCode());

        // Set headers
        foreach ($response->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        // Output body
        echo $response->getBody();
    }

    /**
     * Get router instance
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Get database instance
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }
}
