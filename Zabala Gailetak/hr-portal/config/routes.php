<?php

declare(strict_types=1);

/**
 * Application Routes
 * 
 * Define all application routes here
 */

use HrPortal\Http\Request;
use HrPortal\Http\Response;

$router = $GLOBALS['app']->getRouter() ?? null;

if ($router === null) {
    throw new \RuntimeException('Router not initialized');
}

// ============================================================================
// Web Routes
// ============================================================================

// Home
$router->get('/', function (Request $request): Response {
    return Response::html('<h1>Zabala Gailetak HR Portal</h1><p>Sistema en construcción...</p>');
});

// Auth routes
$router->get('/login', function (Request $request): Response {
    return Response::html('<h1>Login</h1><!-- TODO: Implement login page -->');
});

$router->post('/login', function (Request $request): Response {
    return Response::json(['message' => 'Login endpoint - TODO']);
});

$router->get('/logout', function (Request $request): Response {
    return Response::redirect('/login');
});

// ============================================================================
// API Routes
// ============================================================================

// API Health check
$router->get('/api/health', function (Request $request): Response {
    return Response::json([
        'status' => 'ok',
        'timestamp' => time(),
        'version' => '1.0.0'
    ]);
});

// API Auth
$router->post('/api/auth/login', function (Request $request): Response {
    return Response::json(['message' => 'API Login - TODO']);
});

$router->post('/api/auth/register', function (Request $request): Response {
    return Response::json(['message' => 'API Register - TODO']);
});

$router->post('/api/auth/logout', function (Request $request): Response {
    return Response::json(['message' => 'API Logout - TODO']);
});

// API Employees
$router->get('/api/employees', function (Request $request): Response {
    return Response::json(['message' => 'List employees - TODO']);
});

$router->get('/api/employees/{id}', function (Request $request, string $id): Response {
    return Response::json(['message' => "Get employee $id - TODO"]);
});

$router->post('/api/employees', function (Request $request): Response {
    return Response::json(['message' => 'Create employee - TODO']);
});

$router->put('/api/employees/{id}', function (Request $request, string $id): Response {
    return Response::json(['message' => "Update employee $id - TODO"]);
});

$router->delete('/api/employees/{id}', function (Request $request, string $id): Response {
    return Response::json(['message' => "Delete employee $id - TODO"]);
});

// API Vacations
$router->get('/api/vacations', function (Request $request): Response {
    return Response::json(['message' => 'List vacations - TODO']);
});

$router->post('/api/vacations', function (Request $request): Response {
    return Response::json(['message' => 'Request vacation - TODO']);
});

// API Documents
$router->get('/api/documents', function (Request $request): Response {
    return Response::json(['message' => 'List documents - TODO']);
});

$router->post('/api/documents/upload', function (Request $request): Response {
    return Response::json(['message' => 'Upload document - TODO']);
});

// API Payroll
$router->get('/api/payroll', function (Request $request): Response {
    return Response::json(['message' => 'List payroll - TODO']);
});

// API Chat
$router->get('/api/chat/conversations', function (Request $request): Response {
    return Response::json(['message' => 'List conversations - TODO']);
});

$router->get('/api/chat/messages/{conversationId}', function (Request $request, string $conversationId): Response {
    return Response::json(['message' => "Get messages for conversation $conversationId - TODO"]);
});

$router->post('/api/chat/messages', function (Request $request): Response {
    return Response::json(['message' => 'Send message - TODO']);
});

// API Complaints
$router->get('/api/complaints', function (Request $request): Response {
    return Response::json(['message' => 'List complaints - TODO']);
});

$router->post('/api/complaints', function (Request $request): Response {
    return Response::json(['message' => 'Create complaint - TODO']);
});
