<?php

declare(strict_types=1);

/**
 * Application Routes
 * 
 * Define all application routes here
 */

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Controllers\AuthController;
use ZabalaGailetak\HrPortal\Auth\TokenManager;
use ZabalaGailetak\HrPortal\Auth\SessionManager;
use ZabalaGailetak\HrPortal\Auth\MFA\TOTPService;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Controllers\Web\WebAuthController;
use ZabalaGailetak\HrPortal\Controllers\Web\WebDashboardController;

$router = $GLOBALS['app']->getRouter() ?? null;

if ($router === null) {
    throw new \RuntimeException('Router not initialized');
}

// Initialize services
$db = $GLOBALS['app']->getDatabase();

// 1. Instantiate TokenManager
// phpcs:disable Generic.CodeAnalysis.AssignmentInCondition
$jwtSecret = $_ENV['JWT_SECRET'] ?? null;
if ($jwtSecret === null) {
    throw new \RuntimeException('JWT_SECRET environment variable is required');
}
// phpcs:enable Generic.CodeAnalysis.AssignmentInCondition

$tokenManager = new TokenManager([
    'jwt_secret' => $jwtSecret,
    'jwt_issuer' => $_ENV['APP_URL'] ?? 'https://zabala-gailetak.infinityfreeapp.com',
    'jwt_access_expiry' => 3600,
    'jwt_refresh_expiry' => 604800
]);

// 2. Instantiate SessionManager (Native)
$sessionManager = new SessionManager([
    'session_prefix' => 'hrportal:',
    'session_ttl' => (int)($_ENV['SESSION_LIFETIME'] ?? 28800)
]);

// 3. Instantiate Services
$totpService = new TOTPService();

// 4. Instantiate Controllers
$authController = new AuthController($db, $tokenManager, $sessionManager, $totpService);

$accessControl = new \ZabalaGailetak\HrPortal\Auth\AccessControl();
$employeeController = new \ZabalaGailetak\HrPortal\Controllers\EmployeeController($db, $accessControl);

$auditLogger = new \ZabalaGailetak\HrPortal\Services\AuditLogger($db);
$vacationService = new \ZabalaGailetak\HrPortal\Services\VacationService($db);
$vacationController = new \ZabalaGailetak\HrPortal\Controllers\VacationController($vacationService, $auditLogger);

$webAuthController = new WebAuthController($db);
$webDashboardController = new WebDashboardController();
$webEmployeeController = new \ZabalaGailetak\HrPortal\Controllers\Web\WebEmployeeController($db, $accessControl);
$webVacationController = new \ZabalaGailetak\HrPortal\Controllers\Web\WebVacationController($db, $vacationService);

// ============================================================================
// Web Routes (Server Side Rendering)
// ============================================================================

// Public Routes
$router->get('/', function (Request $request): Response {
    return Response::redirect('/login');
});

$router->get('/login', [$webAuthController, 'loginForm']);
$router->post('/login', [$webAuthController, 'login']);
$router->get('/logout', [$webAuthController, 'logout']);

// Protected Routes
$router->get('/dashboard', [$webDashboardController, 'index']);

// Employees
$router->get('/employees', [$webEmployeeController, 'index']);
$router->get('/employees/create', [$webEmployeeController, 'createForm']);
$router->post('/employees/create', [$webEmployeeController, 'create']);
$router->get('/employees/show/{id}', [$webEmployeeController, 'show']);
$router->get('/employees/edit/{id}', [$webEmployeeController, 'editForm']);
$router->post('/employees/edit/{id}', [$webEmployeeController, 'update']);
$router->post('/employees/delete/{id}', [$webEmployeeController, 'delete']);
$router->get('/employees/export', [$webEmployeeController, 'export']);

// Profile Management (Admin Self-Edit)
$router->get('/employees/profile', [$webEmployeeController, 'profile']);
$router->get('/employees/profile/edit', [$webEmployeeController, 'editProfileForm']);
$router->post('/employees/profile/update', [$webEmployeeController, 'updateProfile']);

// Vacations
$router->get('/vacations', [$webVacationController, 'index']);
$router->get('/vacations/request', [$webVacationController, 'requestForm']);
$router->post('/vacations/request', [$webVacationController, 'create']);
$router->post('/vacations/approve/{id}', [$webVacationController, 'approve']);
$router->post('/vacations/reject/{id}', [$webVacationController, 'reject']);

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
$router->post('/api/auth/login', [$authController, 'login']);
$router->post('/api/auth/logout', [$authController, 'logout']);
$router->post('/api/auth/refresh', [$authController, 'refresh']);
$router->get('/api/auth/me', [$authController, 'me']);

// MFA endpoints
$router->post('/api/auth/mfa/verify', [$authController, 'verifyMfa']);
$router->post('/api/auth/mfa/setup', [$authController, 'setupMfa']);
$router->post('/api/auth/mfa/enable', [$authController, 'enableMfa']);
$router->post('/api/auth/mfa/disable', [$authController, 'disableMfa']);

// API Employees
$router->get('/api/employees', [$employeeController, 'index']);
$router->get('/api/employees/{id}', [$employeeController, 'show']);
$router->post('/api/employees', [$employeeController, 'create']);
$router->put('/api/employees/{id}', [$employeeController, 'update']);
$router->delete('/api/employees/{id}', [$employeeController, 'delete']);
$router->post('/api/employees/{id}/restore', [$employeeController, 'restore']);

// API Vacations
$router->get('/api/vacations/balance', [$vacationController, 'getBalance']);
$router->get('/api/vacations/balance/{employeeId}', [$vacationController, 'getBalanceByEmployee']);
$router->get('/api/vacations/requests', [$vacationController, 'getMyRequests']);
$router->get('/api/vacations/requests/{requestId}', [$vacationController, 'getRequest']);
$router->post('/api/vacations/requests', [$vacationController, 'createRequest']);
$router->post('/api/vacations/requests/{requestId}/cancel', [$vacationController, 'cancel']);
$router->get('/api/vacations/pending/manager', [$vacationController, 'getPendingManagerRequests']);
$router->get('/api/vacations/pending/hr', [$vacationController, 'getPendingHRRequests']);
$router->post('/api/vacations/requests/{requestId}/approve-manager', [$vacationController, 'approveByManager']);
$router->post('/api/vacations/requests/{requestId}/approve-hr', [$vacationController, 'approveByHR']);
$router->post('/api/vacations/requests/{requestId}/reject', [$vacationController, 'reject']);
$router->get('/api/vacations/calendar', [$vacationController, 'getCalendar']);

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
