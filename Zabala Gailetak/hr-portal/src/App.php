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
use ZabalaGailetak\HrPortal\Middleware\AuthenticationMiddleware;
use ZabalaGailetak\HrPortal\Auth\TokenManager;
use ZabalaGailetak\HrPortal\Auth\SessionManager;

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
        // Initialize auth services for middleware
        $jwtSecret = $_ENV['JWT_SECRET'] ?? null;
        if ($jwtSecret === null) {
            throw new \RuntimeException('JWT_SECRET environment variable is required');
        }
        
        $tokenManager = new TokenManager([
            'jwt_secret' => $jwtSecret,
            'jwt_issuer' => $_ENV['APP_URL'] ?? 'https://zabala-gailetak.infinityfreeapp.com',
            'jwt_access_expiry' => 3600,
            'jwt_refresh_expiry' => 604800
        ]);
        
        $sessionManager = new SessionManager([
            'session_prefix' => 'hrportal:',
            'session_ttl' => (int)($_ENV['SESSION_LIFETIME'] ?? 28800)
        ]);
        
        $this->middleware = [
            new ErrorHandlerMiddleware(),
            new SecurityHeadersMiddleware(),
            new AuthenticationMiddleware($tokenManager, $sessionManager),
            new CSRFMiddleware(),
        ];
    }

    /**
     * Load application routes
     */
    private function loadRoutes(): void
    {
        // phpcs:disable
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', dirname(__DIR__, 2));
        }
        // phpcs:enable
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

        // Process through middleware stack (may return modified request or response)
        $middlewareResult = $this->processMiddleware($request);
        
        // If middleware returned a Response, send it directly
        if ($middlewareResult instanceof Response) {
            $this->sendResponse($middlewareResult);
            return;
        }
        
        // If middleware returned a modified Request, use it
        if ($middlewareResult instanceof Request) {
            $request = $middlewareResult;
        }

        // Pass (potentially modified) request to router
        $response = $this->router->dispatch($request);

        // Send response
        $this->sendResponse($response);
    }

    /**
     * Process request through middleware stack
     * Supports request modification by passing the returned request to next middleware
     */
    private function processMiddleware(Request $request): ?Response
    {
        foreach ($this->middleware as $middleware) {
            $result = $middleware->process($request);
            // If result is a Response, return it (error/response case)
            if ($result instanceof Response) {
                return $result;
            }
            // If middleware returns a modified request, use it for subsequent middleware
            if ($result instanceof Request) {
                $request = $result;
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
