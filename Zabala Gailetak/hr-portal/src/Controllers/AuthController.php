<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers;

use ZabalaGailetak\HrPortal\Auth\TokenManager;
use ZabalaGailetak\HrPortal\Auth\SessionManager;
use ZabalaGailetak\HrPortal\Auth\MFA\TOTPService;
use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use Exception;

/**
 * Controlador de Autenticación
 *
 * Maneja login, logout, MFA y gestión de sesiones
 */
class AuthController
{
    private Database $db;
    private TokenManager $tokenManager;
    private SessionManager $sessionManager;
    private TOTPService $totpService;

    public function __construct(
        Database $db,
        TokenManager $tokenManager,
        SessionManager $sessionManager,
        TOTPService $totpService
    ) {
        $this->db = $db;
        $this->tokenManager = $tokenManager;
        $this->sessionManager = $sessionManager;
        $this->totpService = $totpService;
    }

    /**
     * Validar token de autorización y retornar user ID
     */
    private function authenticateRequest(Request $request): ?string
    {
        $token = $this->tokenManager->extractTokenFromHeader($request->getHeader('Authorization') ?? '');

        if (!$token) {
            return null;
        }

        $payload = $this->tokenManager->validateToken($token);

        if (!$payload) {
            return null;
        }

        return $payload->sub ?? null;
    }

    /**
     * POST /api/auth/login
     * Autenticación con email/password
     */
    public function login(Request $request): Response
    {
        try {
            $data = $request->getParsedBody();
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            // Log login attempt
            error_log("Login attempt for email: $email");

            // Validar datos
            if (empty($email) || empty($password)) {
                error_log("Login failed: Email or password empty");
                return Response::json([
                    'error' => 'Email y contraseña son requeridos'
                ], 400);
            }

            // Buscar usuario
            $stmt = $this->db->prepare(
                'SELECT id, email, password_hash, role, mfa_enabled, account_locked, failed_login_attempts 
                FROM users WHERE email = :email'
            );
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if (!$user) {
                error_log("Login failed: User not found - $email");
                return Response::json([
                    'error' => 'Credenciales inválidas'
                ], 401);
            }

            error_log("User found: " . $user['email'] . " (Role: " . $user['role'] . ")");

            // Verificar si la cuenta está bloqueada
            if ($user['account_locked']) {
                error_log("Login failed: Account locked - " . $user['email']);
                return Response::json([
                    'error' => 'Cuenta bloqueada. Contacta con el administrador'
                ], 403);
            }

            // Verificar contraseña
            $passwordMatch = password_verify($password, $user['password_hash']);
            error_log("Password verification: " . ($passwordMatch ? 'SUCCESS' : 'FAILED') .
                " (hash starts with: " . substr($user['password_hash'], 0, 7) . ")");

            if (!$passwordMatch) {
                // Incrementar intentos fallidos
                $this->incrementFailedAttempts($user['id']);

                return Response::json([
                    'error' => 'Credenciales inválidas'
                ], 401);
            }

            // Reset intentos fallidos
            $this->resetFailedAttempts($user['id']);

            error_log("Login successful for: " . $user['email']);

            // Si tiene MFA habilitado, devolver token temporal
            if ($user['mfa_enabled']) {
                $mfaToken = $this->tokenManager->generateMfaToken($user['id']);

                return Response::json([
                    'message' => 'MFA requerido',
                    'mfa_required' => true,
                    'mfa_token' => $mfaToken
                ], 200);
            }

            // Generar tokens y sesión
            $tokens = $this->generateAuthTokens($user);

            // Actualizar last_login
            $this->updateLastLogin($user['id']);

            return Response::json([
                'message' => 'Login exitoso',
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ],
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'session_id' => $tokens['session_id']
            ], 200);
        } catch (Exception $e) {
            error_log("Login exception: " . $e->getMessage());
            return Response::json([
                'error' => 'Error en el login: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/auth/mfa/verify
     * Verificación del código MFA
     */
    public function verifyMfa(Request $request): Response
    {
        try {
            $data = $request->getParsedBody();
            $mfaToken = $data['mfa_token'] ?? '';
            $code = $data['code'] ?? '';

            if (empty($mfaToken) || empty($code)) {
                return Response::json([
                    'error' => 'Token MFA y código son requeridos'
                ], 400);
            }

            // Validar token MFA
            $decoded = $this->tokenManager->validateToken($mfaToken);

            if ($decoded->type !== 'mfa_pending') {
                return Response::json([
                    'error' => 'Token MFA inválido'
                ], 401);
            }

            $userId = $this->tokenManager->getUserId($decoded);

            // Obtener secreto MFA del usuario
            $stmt = $this->db->prepare('SELECT mfa_secret FROM users WHERE id = :id');
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            if (!$user || empty($user['mfa_secret'])) {
                return Response::json([
                    'error' => 'MFA no configurado correctamente'
                ], 400);
            }

            // Verificar código TOTP
            if (!$this->totpService->verifyCode($user['mfa_secret'], $code)) {
                return Response::json([
                    'error' => 'Código MFA inválido'
                ], 401);
            }

            // Obtener datos completos del usuario
            $stmt = $this->db->prepare(
                'SELECT id, email, role FROM users WHERE id = :id'
            );
            $stmt->execute(['id' => $userId]);
            $userData = $stmt->fetch();

            // Generar tokens con MFA verificado
            $userData['mfa_verified'] = true;
            $tokens = $this->generateAuthTokens($userData);

            // Actualizar last_login
            $this->updateLastLogin($userId);

            return Response::json([
                'message' => 'MFA verificado exitosamente',
                'user' => [
                    'id' => $userData['id'],
                    'email' => $userData['email'],
                    'role' => $userData['role']
                ],
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'session_id' => $tokens['session_id']
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'error' => 'Error en verificación MFA: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/auth/mfa/setup
     * Configuración inicial de MFA
     */
    public function setupMfa(Request $request): Response
    {
        try {
            // Autenticar usuario
            $userId = $this->authenticateRequest($request);

            if (!$userId) {
                return Response::json([
                    'error' => 'No autenticado'
                ], 401);
            }

            // Obtener email del usuario
            $stmt = $this->db->prepare('SELECT email FROM users WHERE id = :id');
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            if (!$user) {
                return Response::json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }

            // Generar secreto TOTP
            $secret = $this->totpService->generateSecret();

            // Guardar secreto temporalmente (no activar aún)
            $stmt = $this->db->prepare(
                'UPDATE users SET mfa_secret = :secret WHERE id = :id'
            );
            $stmt->execute([
                'secret' => $secret,
                'id' => $userId
            ]);

            // Generar información de configuración
            $setupInfo = $this->totpService->getMfaSetupInfo($secret, $user['email']);

            // Generar códigos de respaldo
            $backupCodes = $this->totpService->generateBackupCodes();

            return Response::json([
                'message' => 'Configuración MFA iniciada',
                'setup' => [
                    'qr_code' => $setupInfo['qr_code_image'],
                    'secret' => $setupInfo['secret_formatted'],
                    'issuer' => $setupInfo['issuer'],
                    'account' => $setupInfo['account']
                ],
                'backup_codes' => $backupCodes
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'error' => 'Error configurando MFA: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/auth/mfa/enable
     * Activar MFA después de verificar el código
     */
    public function enableMfa(Request $request): Response
    {
        try {
            // Autenticar usuario
            $userId = $this->authenticateRequest($request);

            if (!$userId) {
                return Response::json([
                    'error' => 'No autenticado'
                ], 401);
            }

            $data = $request->getParsedBody();
            $code = $data['code'] ?? '';
            $backupCodes = $data['backup_codes'] ?? [];

            if (empty($code)) {
                return Response::json([
                    'error' => 'Código requerido'
                ], 400);
            }

            // Obtener secreto temporal
            $stmt = $this->db->prepare(
                'SELECT mfa_secret, mfa_enabled FROM users WHERE id = :id'
            );
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            if (!$user || empty($user['mfa_secret'])) {
                return Response::json([
                    'error' => 'MFA no configurado. Ejecuta /setup primero'
                ], 400);
            }

            if ($user['mfa_enabled']) {
                return Response::json([
                    'error' => 'MFA ya está activado'
                ], 400);
            }

            // Verificar código
            if (!$this->totpService->verifyCode($user['mfa_secret'], $code)) {
                return Response::json([
                    'error' => 'Código inválido'
                ], 401);
            }

            // Hashear códigos de respaldo
            $hashedBackupCodes = $this->totpService->hashBackupCodes($backupCodes);

            // Activar MFA
            $stmt = $this->db->prepare(
                'UPDATE users 
                SET mfa_enabled = TRUE, 
                    mfa_backup_codes = :backup_codes,
                    updated_at = NOW()
                WHERE id = :id'
            );
            $stmt->execute([
                'backup_codes' => json_encode($hashedBackupCodes),
                'id' => $userId
            ]);

            return Response::json([
                'message' => 'MFA activado exitosamente'
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'error' => 'Error activando MFA: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/auth/refresh
     * Renovar access token con refresh token
     */
    public function refresh(Request $request): Response
    {
        try {
            $data = $request->getParsedBody();
            $refreshToken = $data['refresh_token'] ?? '';

            if (empty($refreshToken)) {
                return Response::json([
                    'error' => 'Refresh token requerido'
                ], 400);
            }

            // Validar refresh token
            $decoded = $this->tokenManager->validateToken($refreshToken);

            if (!$this->tokenManager->isRefreshToken($decoded)) {
                return Response::json([
                    'error' => 'Token inválido'
                ], 401);
            }

            $userId = $this->tokenManager->getUserId($decoded);

            // Obtener datos del usuario
            $stmt = $this->db->prepare(
                'SELECT id, email, role, mfa_enabled FROM users WHERE id = :id'
            );
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            if (!$user) {
                return Response::json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }

            // Generar nuevo access token
            $user['mfa_verified'] = $user['mfa_enabled']; // Si tiene MFA, asumir verificado
            $accessToken = $this->tokenManager->generateAccessToken($user);

            return Response::json([
                'access_token' => $accessToken
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'error' => 'Error renovando token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/auth/logout
     * Cerrar sesión
     */
    public function logout(Request $request): Response
    {
        try {
            // Autenticar usuario
            $userId = $this->authenticateRequest($request);

            if (!$userId) {
                return Response::json([
                    'error' => 'No autenticado'
                ], 401);
            }

            // Obtener session_id del body o destruir todas las sesiones
            $data = $request->getParsedBody();
            $sessionId = $data['session_id'] ?? null;

            if ($sessionId) {
                $this->sessionManager->destroySession($sessionId);
            } else {
                // Destruir todas las sesiones del usuario
                $this->sessionManager->destroyAllUserSessions($userId);
            }

            return Response::json([
                'message' => 'Logout exitoso'
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'error' => 'Error en logout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/auth/me
     * Obtener información del usuario autenticado
     */
    public function me(Request $request): Response
    {
        try {
            // Extraer y validar token
            $token = $this->tokenManager->extractTokenFromHeader($request->getHeader('Authorization') ?? '');

            if (!$token) {
                return Response::json([
                    'error' => 'Token no proporcionado'
                ], 401);
            }

            $payload = $this->tokenManager->validateToken($token);

            if (!$payload) {
                return Response::json([
                    'error' => 'Token inválido o expirado'
                ], 401);
            }

            $userId = $payload->sub ?? null;

            if (!$userId) {
                return Response::json([
                    'error' => 'No autenticado'
                ], 401);
            }

            $stmt = $this->db->prepare(
                'SELECT u.id, u.email, u.role, u.mfa_enabled, u.last_login, u.created_at,
                        e.first_name, e.last_name, e.employee_number, e.position, e.department_id
                FROM users u
                LEFT JOIN employees e ON u.id = e.user_id
                WHERE u.id = :id'
            );
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            if (!$user) {
                return Response::json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }

            return Response::json([
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'error' => 'Error obteniendo usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    // Métodos auxiliares privados

    private function generateAuthTokens(array $user): array
    {
        $accessToken = $this->tokenManager->generateAccessToken($user);
        $refreshToken = $this->tokenManager->generateRefreshToken($user['id']);

        // Crear sesión nativa
        $sessionId = $this->sessionManager->createSession($user['id'], [
            'email' => $user['email'],
            'role' => $user['role'],
            'mfa_verified' => $user['mfa_verified'] ?? false
        ]);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'session_id' => $sessionId
        ];
    }

    private function incrementFailedAttempts(string $userId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users 
            SET failed_login_attempts = failed_login_attempts + 1,
                account_locked = CASE WHEN failed_login_attempts >= 4 THEN TRUE ELSE FALSE END
            WHERE id = :id'
        );
        $stmt->execute(['id' => $userId]);
    }

    private function resetFailedAttempts(string $userId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET failed_login_attempts = 0 WHERE id = :id'
        );
        $stmt->execute(['id' => $userId]);
    }

    private function updateLastLogin(string $userId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET last_login = NOW() WHERE id = :id'
        );
        $stmt->execute(['id' => $userId]);
    }
}
