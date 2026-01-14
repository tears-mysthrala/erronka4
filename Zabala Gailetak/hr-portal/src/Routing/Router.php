<?php

declare(strict_types=1);

namespace HrPortal\Routing;

use HrPortal\Http\Request;
use HrPortal\Http\Response;

/**
 * Application Router
 * 
 * Handles HTTP routing and dispatching
 */
class Router
{
    private array $routes = [];
    
    /**
     * Register a GET route
     */
    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Register a POST route
     */
    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Register a PUT route
     */
    public function put(string $path, callable $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Register a DELETE route
     */
    public function delete(string $path, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Add a route
     */
    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'pattern' => $this->compilePattern($path),
        ];
    }
    
    /**
     * Compile path pattern to regex
     */
    private function compilePattern(string $path): string
    {
        // Convert {param} to regex capturing group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Dispatch request to appropriate handler
     */
    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                try {
                    $response = call_user_func_array($route['handler'], array_merge([$request], $matches));
                    
                    if (!$response instanceof Response) {
                        throw new \RuntimeException('Handler must return Response instance');
                    }
                    
                    return $response;
                } catch (\Throwable $e) {
                    error_log('Route handler error: ' . $e->getMessage());
                    return Response::json([
                        'error' => 'Internal server error'
                    ], 500);
                }
            }
        }
        
        // No route matched - 404
        return Response::json([
            'error' => 'Not found'
        ], 404);
    }
}
