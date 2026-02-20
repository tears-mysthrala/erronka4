<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Auth;

use DateTime;
use DateInterval;
use Exception;
use stdClass;

/**
 * JWT Token Manager (Native Implementation)
 *
 * Replaces Firebase\JWT library for Zero Trust compliance.
 * Implements native HMAC-SHA256 signature and Base64Url encoding.
 */
class TokenManager
{
    private string $secretKey;
    private string $algorithm = 'HS256';
    private int $accessTokenExpiry = 3600; // 1 hora
    private int $refreshTokenExpiry = 604800; // 7 días
    private string $issuer;

    public function __construct(array $config)
    {
        $this->secretKey = $config['jwt_secret'] ?? throw new Exception('JWT secret key not configured');
        $this->issuer = $config['jwt_issuer'] ?? 'hr-portal.zabalagailetak.com';

        // CVE-2025-45769: Enforce minimum 32-byte key for HS256 (256-bit minimum)
        if (strlen($this->secretKey) < 32) {
            throw new Exception(
                'JWT secret key must be at least 32 characters long for HS256 (256-bit minimum). ' .
                'Current length: ' . strlen($this->secretKey) . '. ' .
                'Generate a new key with: openssl rand -base64 32'
            );
        }

        if (isset($config['jwt_access_expiry'])) {
            $this->accessTokenExpiry = (int) $config['jwt_access_expiry'];
        }

        if (isset($config['jwt_refresh_expiry'])) {
            $this->refreshTokenExpiry = (int) $config['jwt_refresh_expiry'];
        }
    }

    /**
     * Genera un access token JWT
     */
    public function generateAccessToken(array $userData): string
    {
        $now = new DateTime();
        $expiry = (clone $now)->add(new DateInterval("PT{$this->accessTokenExpiry}S"));

        $payload = [
            'iss' => $this->issuer,
            'iat' => $now->getTimestamp(),
            'exp' => $expiry->getTimestamp(),
            'sub' => (string)$userData['id'],
            'type' => 'access',
            'data' => [
                'email' => $userData['email'] ?? null,
                'role' => $userData['role'] ?? 'employee',
                'employee_id' => $userData['employee_id'] ?? null,
                'mfa_verified' => $userData['mfa_verified'] ?? false,
            ]
        ];

        return $this->encode($payload);
    }

    /**
     * Genera un refresh token JWT
     */
    public function generateRefreshToken(string $userId): string
    {
        $now = new DateTime();
        $expiry = (clone $now)->add(new DateInterval("PT{$this->refreshTokenExpiry}S"));

        $payload = [
            'iss' => $this->issuer,
            'iat' => $now->getTimestamp(),
            'exp' => $expiry->getTimestamp(),
            'sub' => $userId,
            'type' => 'refresh',
            'jti' => bin2hex(random_bytes(16)) // Token ID único
        ];

        return $this->encode($payload);
    }

    /**
     * Genera un token temporal para verificación MFA
     */
    public function generateMfaToken(string $userId): string
    {
        $now = new DateTime();
        $expiry = (clone $now)->add(new DateInterval('PT5M')); // 5 minutos

        $payload = [
            'iss' => $this->issuer,
            'iat' => $now->getTimestamp(),
            'exp' => $expiry->getTimestamp(),
            'sub' => $userId,
            'type' => 'mfa_pending'
        ];

        return $this->encode($payload);
    }

    /**
     * Encode JWT (Native)
     */
    private function encode(array $payload): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]);
        $payloadJson = json_encode($payload);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payloadJson);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Valida y decodifica un token JWT
     */
    public function validateToken(string $token): ?object
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new Exception('Formato de token inválido', 401);
        }

        [$headerB64, $payloadB64, $sigB64] = $parts;

        // Verify Signature
        $signature = $this->base64UrlDecode($sigB64);
        $expectedSignature = hash_hmac('sha256', $headerB64 . "." . $payloadB64, $this->secretKey, true);

        if (!hash_equals($expectedSignature, $signature)) {
            throw new Exception('Firma del token inválida', 401);
        }

        // Decode Payload
        $payload = json_decode($this->base64UrlDecode($payloadB64));

        if (!$payload) {
            throw new Exception('Payload del token corrupto', 401);
        }

        // Check Expiry
        if (isset($payload->exp) && $payload->exp < time()) {
            throw new Exception('Token ha expirado', 401);
        }

        // Check Issuer
        if (isset($payload->iss) && $payload->iss !== $this->issuer) {
            throw new Exception('Emisor del token inválido', 401);
        }

        return $payload;
    }

    /**
     * Helper: Base64Url Encode
     */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Helper: Base64Url Decode
     */
    private function base64UrlDecode(string $data): string
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * Extrae el token del header Authorization
     */
    public function extractTokenFromHeader(string $authHeader): ?string
    {
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }

        return substr($authHeader, 7);
    }

    /**
     * Verifica si el token es de tipo access
     */
    public function isAccessToken(object $decoded): bool
    {
        return isset($decoded->type) && $decoded->type === 'access';
    }

    /**
     * Verifica si el token es de tipo refresh
     */
    public function isRefreshToken(object $decoded): bool
    {
        return isset($decoded->type) && $decoded->type === 'refresh';
    }

    /**
     * Obtiene el ID de usuario del token
     */
    public function getUserId(object $decoded): ?string
    {
        return $decoded->sub ?? null;
    }

    /**
     * Obtiene los datos del usuario del token
     */
    public function getUserData(object $decoded): ?object
    {
        return $decoded->data ?? null;
    }

    /**
     * Verifica si el usuario tiene MFA verificado en el token
     */
    public function isMfaVerified(object $decoded): bool
    {
        return $decoded->data->mfa_verified ?? false;
    }

    /**
     * Obtiene el rol del usuario del token
     */
    public function getUserRole(object $decoded): ?string
    {
        return $decoded->data->role ?? null;
    }

    /**
     * Verifica si el token está cerca de expirar (< 10 minutos)
     */
    public function isTokenExpiringSoon(object $decoded): bool
    {
        if (!isset($decoded->exp)) {
            return true;
        }

        $expiryTime = $decoded->exp;
        $currentTime = time();
        $timeUntilExpiry = $expiryTime - $currentTime;

        return $timeUntilExpiry < 600; // Menos de 10 minutos
    }

    /**
     * Renueva un access token usando un refresh token válido
     */
    public function renewAccessToken(string $refreshToken, array $userData): string
    {
        $decoded = $this->validateToken($refreshToken);

        if (!$this->isRefreshToken($decoded)) {
            throw new Exception('Token no es un refresh token', 400);
        }

        return $this->generateAccessToken($userData);
    }
}
