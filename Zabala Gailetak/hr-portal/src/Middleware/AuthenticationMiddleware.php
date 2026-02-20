<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Middleware;

use ZabalaGailetak\HrPortal\Auth\TokenManager;
use ZabalaGailetak\HrPortal\Auth\SessionManager;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use Exception;
use Throwable;

/**
 * Authentication Middleware
 *
 * Valida el token JWT y carga información del usuario en el request
 */
class AuthenticationMiddleware
{
    private TokenManager $tokenManager;
    private SessionManager $sessionManager;
    private array $excludedPaths = [
        '/api/health',
        '/api/auth/login',
        '/api/auth/refresh',
        '/api/auth/mfa/verify',
        '/login',
        '/logout',
        '/',  // Redirects to login
    ];

    public function __construct(TokenManager $tokenManager, SessionManager $sessionManager)
    {
        $this->tokenManager = $tokenManager;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Process request - validate JWT and add user attributes
     * Returns modified Request on success, Response on auth failure, or null to skip
     */
    public function process(Request $request): Request|Response|null
    {
        $path = $request->getUri();

        // Excluir rutas públicas
        if ($this->isExcludedPath($path)) {
            return null;
        }

        // Web routes use session auth, skip JWT for non-API routes
        if (!str_starts_with($path, '/api/')) {
            return null;
        }

        // Obtener header Authorization
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader)) {
            return Response::json([
                'error' => 'Token de autenticación requerido'
            ], 401);
        }

        try {
            // Extraer token del header
            $token = $this->tokenManager->extractTokenFromHeader($authHeader);

            if ($token === null) {
                return Response::json([
                    'error' => 'Formato de token inválido'
                ], 401);
            }

            // Validar token
            $decoded = $this->tokenManager->validateToken($token);

            // Verificar que sea un access token
            if (!$this->tokenManager->isAccessToken($decoded)) {
                return Response::json([
                    'error' => 'Tipo de token inválido'
                ], 401);
            }

            // Obtener datos del usuario
            $userId = $this->tokenManager->getUserId($decoded);
            $userData = $this->tokenManager->getUserData($decoded);
            $userRole = $this->tokenManager->getUserRole($decoded);

            // Build user array for controllers (id, role, email)
            $user = [
                'id' => $userId,
                'email' => $userData->email ?? null,
                'role' => $userRole
            ];

            // Añadir información al request para uso posterior
            $request = $request->withAttribute('user', $user);
            $request = $request->withAttribute('user_id', $userId);
            $request = $request->withAttribute('user_email', $userData->email ?? null);
            $request = $request->withAttribute('user_role', $userRole);
            $request = $request->withAttribute('mfa_verified', $this->tokenManager->isMfaVerified($decoded));
            $request = $request->withAttribute('token_decoded', $decoded);

            // Si hay session ID en el header, validar sesión
            $sessionId = $request->getHeaderLine('X-Session-ID');
            if (!empty($sessionId)) {
                if (!$this->sessionManager->sessionExists($sessionId)) {
                    return Response::json([
                        'error' => 'Sesión inválida o expirada'
                    ], 401);
                }

                // Actualizar última actividad
                $this->sessionManager->refreshSession($sessionId);
                $request = $request->withAttribute('session_id', $sessionId);
            }

            // Devolver request modificada
            return $request;
        } catch (Throwable $e) {
            // Catch all errors (Exception, Error, etc.) to prevent stack trace leaks
            error_log('[AuthenticationMiddleware] ' . get_class($e) . ': ' . $e->getMessage());
            
            // Return generic 401 for any authentication failure (fail secure)
            return Response::json([
                'error' => 'Autenticación fallida'
            ], 401);
        }
    }

    private function isExcludedPath(string $path): bool
    {
        foreach ($this->excludedPaths as $excludedPath) {
            if ($path === $excludedPath || str_starts_with($path, $excludedPath)) {
                return true;
            }
        }

        return false;
    }

    public function addExcludedPath(string $path): void
    {
        $this->excludedPaths[] = $path;
    }
}
