<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use DateTime;
use DateInterval;
use Exception;

/**
 * JWT Token Manager
 * 
 * Gestiona la creación, validación y renovación de tokens JWT
 * para autenticación de usuarios en el sistema
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
            'sub' => $userData['id'],
            'type' => 'access',
            'data' => [
                'email' => $userData['email'] ?? null,
                'role' => $userData['role'] ?? 'employee',
                'employee_id' => $userData['employee_id'] ?? null,
                'mfa_verified' => $userData['mfa_verified'] ?? false,
            ]
        ];
        
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
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
        
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }
    
    /**
     * Valida y decodifica un token JWT
     */
    public function validateToken(string $token): ?object
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            
            // Verificar issuer
            if ($decoded->iss !== $this->issuer) {
                return null;
            }
            
            return $decoded;
        } catch (ExpiredException $e) {
            throw new Exception('Token ha expirado', 401);
        } catch (SignatureInvalidException $e) {
            throw new Exception('Firma del token inválida', 401);
        } catch (BeforeValidException $e) {
            throw new Exception('Token aún no es válido', 401);
        } catch (Exception $e) {
            throw new Exception('Token inválido: ' . $e->getMessage(), 401);
        }
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
        
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
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
